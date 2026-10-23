<?php

if( $_SERVER['HTTP_REFERER'] == "" ){
	echo '<script type="text/javascript">window.location.href="/"</script>';
	exit;
}

?>
