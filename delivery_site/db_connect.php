
<?php
// db_connect.php：用來連接 MySQL 資料庫（使用 PDO）

$host = "localhost";           // 主機名稱（本機就是 localhost）
$dbname = "delivery_site";     // 資料庫名稱（你要連接的資料庫）
$username = "root";            // 資料庫使用者名稱（XAMPP 預設是 root）
$password = "";                // 密碼（XAMPP 預設為空字串）

try {
    // 建立 PDO 資料庫連線（包含字元編碼 utf8）
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // 設定 PDO 的錯誤模式為「丟出例外」模式
    // 這樣如果 SQL 發生錯誤，可以方便偵錯
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 如果連線失敗，印出錯誤訊息並停止程式
    die("資料庫連線失敗：" . $e->getMessage());
}
?>

