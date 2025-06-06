
<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // 登入成功
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // 根據角色進入不同的畫面
        if ($user['role'] === 'admin') {
            header("Location: 管理員.php"); // 管理員頁面
            exit;
        } elseif ($user['role'] === 'merchant') {
            header("Location: store1.php?id=10"); // 店家頁面
            exit;
        } elseif ($user['role'] === 'user') {
            header("Location: 首頁.php"); // 會員頁面
            exit;
        } else {
            echo "未知的使用者角色，請聯繫管理員！<a href='小夫我要進了.php'>回登入頁</a>";
            exit;
        }

    } else {
        echo "帳號或密碼錯誤！<a href='小夫我要進了.php'>請重試</a>";
        exit;
    }

} else {
    header("Location: 小夫我要進了.php");
    exit;
}
?>
