<?php
require_once 'db_connect.php'; // 連線資料庫

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])) {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // 確認兩次密碼是否一致
    if ($password !== $confirm_password) {
        echo "<script>alert('兩次密碼輸入不一致！'); window.location.href='註冊.php';</script>";
        exit;
    }

    // 檢查帳號是否已存在
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    if ($stmt->fetch()) {
        echo "<script>alert('帳號已存在！'); window.location.href='註冊.php';</script>";
        exit;
    }

    // 密碼加密
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 新增使用者
    $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $hashed_password, 'user']);

    // 註冊成功 → 彈跳視窗 + 轉跳
    echo "<script>alert('註冊成功！即將帶您登入。'); window.location.href='小夫我要進了.php';</script>";
    exit;

} else {
    // 如果不是正確的表單送出，跳回註冊頁
    header("Location: 註冊.php");
    exit;
}
?>
