
<?php
session_start();

// 如果尚未登入，導回登入頁
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 顯示登入訊息
echo "歡迎回來，" . $_SESSION['username'] . "！<br>";
echo "您的身份是：" . $_SESSION['role'] . "<br>";

echo '<a href="logout.php">登出</a>';
?>
