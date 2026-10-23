<?php
/**
 * 文件操作工具类
 * 
 * 提供全面的文件/目录操作功能，包括创建、复制、移动、删除等操作。
 * 所有方法均为静态方法，可直接通过类名调用。
 * 
 * @example
 * FileUtil::createDir('a/1/2/3');                    // 递归创建目录
 * FileUtil::createFile('b/1/2/3.txt');               // 创建文件（自动创建父目录）
 * FileUtil::copyDir('source', 'destination');        // 递归复制目录
 * FileUtil::moveFile('old.txt', 'new.txt');          // 移动/重命名文件
 * FileUtil::unlinkDir('temp');                       // 递归删除目录
 */
 //try {
    // 创建目录
    //FileUtil::createDir('documents/reports/2025', 0775);
    
    // 创建文件
    //FileUtil::createFile('documents/reports/2025/Q1.xlsx');
    
    // 复制目录
    ///FileUtil::copyDir('documents', 'backup/documents', true);
    
    // 移动文件
    //FileUtil::moveFile('old.txt', 'new_location/renamed.txt');
    
    // 删除目录
    //FileUtil::unlinkDir('temp_files');
    
//} catch (InvalidArgumentException $e) {
   // echo "操作失败: " . $e->getMessage();
//}
 
 
 
 
 
 
class FileUtilModule
{
    /**
     * 创建目录（递归创建）
     *
     * @param string $path 要创建的目录路径
     * @param int $mode 目录权限（默认0755）
     * @param bool $recursive 是否递归创建（默认true）
     * @return bool 创建成功返回true，失败返回false
     * @throws InvalidArgumentException 当路径无效时抛出异常
     */
    public static function createDir(string $path, int $mode = 0755, bool $recursive = true): bool
    {
        if (empty($path)) {
            throw new InvalidArgumentException("目录路径不能为空");
        }

        $path = rtrim($path, '/\\') . DIRECTORY_SEPARATOR;
        
        if (is_dir($path)) {
            return true;
        }

        return mkdir($path, $mode, $recursive);
    }

    /**
     * 创建文件（自动创建父目录）
     *
     * @param string $filePath 文件完整路径
     * @param bool $overwrite 是否覆盖已存在文件（默认false）
     * @return bool 创建成功返回true，失败返回false
     * @throws InvalidArgumentException 当文件路径无效时抛出异常
     */
    public static function createFile(string $filePath, bool $overwrite = false): bool
    {
        if (empty($filePath)) {
            throw new InvalidArgumentException("文件路径不能为空");
        }

        $dir = dirname($filePath);
        
        if (!empty($dir) && !is_dir($dir)) {
            self::createDir($dir);
        }

        if (file_exists($filePath) && !$overwrite) {
            return false;
        }

        return touch($filePath);
    }

    /**
     * 复制目录（递归复制）
     *
     * @param string $source 源目录路径
     * @param string $destination 目标目录路径
     * @param bool $overwrite 是否覆盖目标文件（默认false）
     * @return bool 复制成功返回true，失败返回false
     * @throws InvalidArgumentException 当源目录不存在时抛出异常
     */
    public static function copyDir(string $source, string $destination, bool $overwrite = false): bool
    {
        if (!is_dir($source)) {
            throw new InvalidArgumentException("源目录不存在");
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $targetPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();

            if ($item->isDir()) {
                self::createDir($targetPath);
            } else {
                self::copyFile($item->getRealPath(), $targetPath, $overwrite);
            }
        }

        return true;
    }

    /**
     * 复制文件
     *
     * @param string $source 源文件路径
     * @param string $destination 目标文件路径
     * @param bool $overwrite 是否覆盖目标文件（默认false）
     * @return bool 复制成功返回true，失败返回false
     * @throws InvalidArgumentException 当源文件不存在时抛出异常
     */
    public static function copyFile(string $source, string $destination, bool $overwrite = false): bool
    {
        if (!file_exists($source)) {
            throw new InvalidArgumentException("源文件不存在");
        }

        $dir = dirname($destination);
        
        if (!empty($dir) && !is_dir($dir)) {
            self::createDir($dir);
        }

        if (file_exists($destination) && !$overwrite) {
            return false;
        }

        return copy($source, $destination);
    }

    /**
     * 移动/重命名目录
     *
     * @param string $source 源目录路径
     * @param string $destination 目标目录路径
     * @param bool $overwrite 是否覆盖目标文件（默认false）
     * @return bool 移动成功返回true，失败返回false
     * @throws InvalidArgumentException 当源目录不存在时抛出异常
     */
    public static function moveDir(string $source, string $destination, bool $overwrite = false): bool
    {
        if (!is_dir($source)) {
            throw new InvalidArgumentException("源目录不存在");
        }

        return rename($source, $destination);
    }

    /**
     * 移动/重命名文件
     *
     * @param string $source 源文件路径
     * @param string $destination 目标文件路径
     * @param bool $overwrite 是否覆盖目标文件（默认false）
     * @return bool 移动成功返回true，失败返回false
     * @throws InvalidArgumentException 当源文件不存在时抛出异常
     */
    public static function moveFile(string $source, string $destination, bool $overwrite = false): bool
    {
        if (!file_exists($source)) {
            throw new InvalidArgumentException("源文件不存在");
        }

        $dir = dirname($destination);
        
        if (!empty($dir) && !is_dir($dir)) {
            self::createDir($dir);
        }

        if (file_exists($destination) && !$overwrite) {
            return false;
        }

        return rename($source, $destination);
    }

    /**
     * 删除目录（递归删除）
     *
     * @param string $path 要删除的目录路径
     * @return bool 删除成功返回true，失败返回false
     * @throws InvalidArgumentException 当目录不存在时抛出异常
     */
    public static function unlinkDir(string $path): bool
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("目录不存在");
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        return rmdir($path);
    }

    /**
     * 删除文件
     *
     * @param string $path 要删除的文件路径
     * @return bool 删除成功返回true，失败返回false
     * @throws InvalidArgumentException 当文件不存在时抛出异常
     */
    public static function unlinkFile(string $path): bool
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("文件不存在");
        }

        return unlink($path);
    }
}
?>