
<?php
session_start();
session_destroy(); // 清除所有 Session
header("Location: 你好.php");
exit;
?>
