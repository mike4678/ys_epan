<?php
declare(strict_types=1);

class DatabaseException extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Database Error: $message", $code, $previous);
    }
}

class ConnectModule
{
    private ?mysqli $connection;
    private string $charset;
    private string $database;
    private ?mysqli_result $result = null;
	private array $queryCache = [];

    public function __construct(
        private string $host,
        private string $username,
        private string $password,
        string $database = '',
        string $charset = 'utf8mb4'
    ) {
        $this->charset = strtolower(str_replace('-', '', $charset));
        $this->database = $database;
        $this->connect();
    }

    private function connect(): void
    {
        $this->connection = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        if (!$this->connection) {
            throw new DatabaseException('Connection failed: ' . mysqli_connect_error());
        }

        $this->setCharset();
        $this->selectDatabase();
    }

    private function setCharset(): void
    {
        if (!mysqli_set_charset($this->connection, $this->charset)) {
            throw new DatabaseException('Setting charset failed: ' . $this->getError());
        }
    }

    private function selectDatabase(): void
    {
        if (!empty($this->database) && !mysqli_select_db($this->connection, $this->database)) {
            throw new DatabaseException('Selecting database failed: ' . $this->getError());
        }
    }

    public function query(string $sql): mysqli_result|false
    {
        if (isset($this->queryCache[$sql])) 
		{
			return $this->queryCache[$sql];
		}
		
	   $this->result = mysqli_query($this->connection, $sql);
        
        if ($this->result === false) {
            throw new DatabaseException("Query failed: $sql - " . $this->getError());
        }
		if ($this->result !== false) {
			$this->queryCache[$sql] = $this->result;
		}
        return $this->result;
    }

    public function prepare(string $sql): mysqli_stmt|false
    {
        $stmt = mysqli_prepare($this->connection, $sql);
        
        if ($stmt === false) {
            throw new DatabaseException("Prepare failed: $sql - " . $this->getError());
        }

        return $stmt;
    }

    public function execute(mysqli_stmt $stmt, array $params = [], string $types = ''): mysqli_stmt|false
    {
        if ($types && $params) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new DatabaseException("Execute failed: " . $this->getError());
        }

        return $stmt;
    }

    public function fetchAll(mysqli_result $result): array
    {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function fetchRow(mysqli_result $result): array|null
    {
        return mysqli_fetch_assoc($result);
    }

    public function insertId(): int|string|false
    {
        return mysqli_insert_id($this->connection);
    }

    public function affectedRows(): int|false
    {
        return mysqli_affected_rows($this->connection);
    }

    public function escapeString(string $string): string
    {
        return mysqli_real_escape_string($this->connection, $string);
    }

    public function getVersion(): string
    {
        return mysqli_get_server_info($this->connection);
    }

    public function close(): void
    {
        if ($this->connection instanceof mysqli && !$this->connection->connect_error) 
		{
			$this->connection->close();
			$this->connection = null; // 防止后续误用
		}
    }

    private function getError(): string
    {
        return mysqli_error($this->connection);
    }

    // 事务处理
    public function beginTransaction(): void
    {
        mysqli_begin_transaction($this->connection);
    }

    public function commit(): void
    {
        mysqli_commit($this->connection);
    }

    public function rollback(): void
    {
        mysqli_rollback($this->connection);
    }

    // 快捷查询方法
    public function select(string $table, string $columns = '*', string $condition = ''): array
    {
        $sql = "SELECT $columns FROM $table";
        $sql .= $condition ? " WHERE $condition" : '';
        $result = $this->query($sql);
        return $this->fetchAll($result);
    }

    public function insert(string $table, array $data): int|string|false
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        $stmt = $this->prepare($sql);
        $types = str_repeat('s', count($data));
        $this->execute($stmt, array_values($data), $types);
        
        return $this->insertId();
    }

    public function update(string $table, array $data, string $condition): int|false
    {
        $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $sql = "UPDATE $table SET $set WHERE $condition";
        
        $stmt = $this->prepare($sql);
        $types = str_repeat('s', count($data));
        $this->execute($stmt, array_values($data), $types);
        
        return $this->affectedRows();
    }

    public function delete(string $table, string $condition): int|false
    {
        $sql = "DELETE FROM $table WHERE $condition";
        $this->query($sql);
        return $this->affectedRows();
    }
	
	public function fetchColumn(mysqli_result $result, int $columnIndex = 0): mixed
	{
		$row = mysqli_fetch_row($result);
		return $row[$columnIndex] ?? null;
	}
}

// 使用示例：
//try {
//    $db = new Db(
 //       host: 'localhost',
 //       username: 'root',
 //       password: 'password',
  //      database: 'testdb',
  //      charset: 'utf8mb4'
  //  );

    // 事务示例
 //   $db->beginTransaction();
    
 //   try {
//        $db->insert('users', ['name' => 'John', 'email' => 'john@example.com']);
 //       $db->update('users', ['name' => 'Johnny'], 'id = 1');
 //       $db->commit();
//    } catch (DatabaseException $e) {
 //       $db->rollback();
 //       throw $e;
 //   }

//} catch (DatabaseException $e) {
//    error_log($e->getMessage());
//    echo "Database error occurred";
//} finally {
//    $db->close();
//}
?>