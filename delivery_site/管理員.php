<?php
session_start();

// ========== 資料庫連線 ==========
$host = "localhost";
$dbname = "delivery_site";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}

// 確認是否為管理員
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: 小夫我要進了.php");
    exit;
}

// 刪除餐廳處理（含留言關聯）
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // 先刪除關聯留言
    $stmt = $pdo->prepare("DELETE FROM comments WHERE restaurant_id = :id");
    $stmt->execute(['id' => $delete_id]);

    // 再刪除餐廳
    $stmt = $pdo->prepare("DELETE FROM restaurants WHERE id = :id");
    $stmt->execute(['id' => $delete_id]);

    header("Location: 管理員.php?status=deleted");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<title>管理員後台｜餐廳管理</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    body { background-color: #FFF8E1; font-family: Arial, sans-serif; }
</style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4"> 歡迎<?php echo htmlspecialchars($_SESSION['username']); ?>！</h1>
<td><a href="logout.php" class="btn btn-sm btn-danger" onclick='return '>登出</a></td>
    <?php
    // 顯示所有餐廳
    $stmt = $pdo->query("SELECT * FROM restaurants ORDER BY id ASC");
    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>ID</th><th>名稱</th><th>圖片</th><th>描述</th><th>操作</th></tr></thead><tbody>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td><img src='" . htmlspecialchars($row['image_path']) . "' alt='餐廳圖' style='max-width:100px;'></td>";
        echo "<td>" . nl2br(htmlspecialchars($row['description'])) . "</td>";
        echo "<td>
            <a href='管理員.php?delete_id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"確定要刪除嗎？\");'>刪除</a>
        </td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
    ?>

    
</div>

<?php
// SweetAlert 刪除成功提示
if (isset($_GET['status']) && $_GET['status'] === 'deleted') {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: '已刪除！',
            text: '餐廳已成功刪除。'
        });
    </script>";
}
?>
</body>
</html>
