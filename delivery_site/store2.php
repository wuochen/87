<?php
session_start();
include("db_connect.php");

// 確認是否為店家
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'merchant') {
    header("Location: 小夫我要進了.php");
    exit;
}

// 新增餐廳處理（在送出表單後執行）
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // 檔案資訊
    $image = $_FILES['image_file'];
    $image_name = $image['name'];
    $image_tmp = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];

    if ($name !== '' && $description !== '' && $image_error === 0) {
        if ($image_size <= 5 * 1024 * 1024) { // 5MB 限制
            $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($ext, $allowed_ext)) {
                $new_image_name = uniqid('restaurant_', true) . '.' . $ext;
                $upload_dir = 'images/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $upload_path = $upload_dir . $new_image_name;

                if (move_uploaded_file($image_tmp, $upload_path)) {
                    // 寫入資料庫
                    $stmt = $pdo->prepare("INSERT INTO restaurants (name, image_path, description) VALUES (:name, :image, :desc)");
                    $stmt->execute([
                        'name' => $name,
                        'image' => $upload_path,
                        'desc' => $description
                    ]);

                    header("Location: " . $_SERVER['PHP_SELF'] . "?status=success");
                    exit;
                } else {
                    $error = "檔案移動失敗！";
                }
            } else {
                $error = "不允許的檔案格式！";
            }
        } else {
            $error = "檔案大小超過限制！";
        }
    } else {
        $error = "請填寫完整資料並選擇圖片！";
    }
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<title>速食宇宙｜店家後台新增餐廳</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    body { background-color: #FFF8E1; font-family: Arial, sans-serif; color: #333; }
    .navbar { background-color: #FFC107 !important; }
    .container { max-width: 800px; margin: 40px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .bgm-btn { background-color: #FFC107; border: none; color: #fff; padding: 8px 12px; border-radius: 5px; }
    .bgm-btn:hover { background-color: #FFB300; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <span class="navbar-brand">店家管理後台</span>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="store1.php">首頁</a></li> 
            </ul>
            <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="logout.php">登出</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container">
    <h2 class="mb-4">➕ 新增餐廳</h2>

    <?php
    if (isset($error)) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
    ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>餐廳名稱：</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>餐廳圖片上傳：</label>
            <input type="file" name="image_file" class="form-control" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label>餐廳介紹：</label>
            <textarea name="description" rows="4" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">新增餐廳</button>
    </form>
</div>

<?php
// SweetAlert 成功提示
if (isset($_GET['status']) && $_GET['status'] === 'success') {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: '新增成功！',
            text: '餐廳已成功新增並上傳圖片！'
        });
    </script>";
}

?>

<!-- 背景音樂 -->
<audio id="bgm" loop>
    <source src="mp3/music3.mp3" type="audio/mpeg">
</audio>
<button class="bgm-btn" onclick="toggleMusic()" style="position: fixed; bottom: 20px; right: 20px;">🎵 音樂播放/暫停</button>

<script>
function toggleMusic() {
    const audio = document.getElementById('bgm');
    if (audio.paused) audio.play();
    else audio.pause();
}
</script>

</body>
</html>
