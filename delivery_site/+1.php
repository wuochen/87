<?php
require_once 'db_connect.php';

$username = 'chen';
$password = '970527';

// 加密密碼（使用 password_hash，比 sha256 安全很多！）
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 新增帳號
$sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username, $hashed_password, 'user']);

echo "帳號新增完成";
?>
