<?php
session_start();
include("db_connect.php"); // 使用你提供的 PDO 連線

// 檢查是否有指定餐廳 ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // 送出留言處理（一定要放在任何 HTML 輸出前！）
    if (isset($_POST['submit_comment']) && isset($_SESSION['username'])) {
        $username = $_SESSION['username']; // 強制使用登入者名稱
        $content = $_POST['content'];

        $stmt = $pdo->prepare("INSERT INTO comments (restaurant_id, username, content) VALUES (:rid, :user, :content)");
        $stmt->execute(['rid' => $id, 'user' => $username, 'content' => $content]);

        header("Location: 肯德基.php?id=$id&comment=success&name=" . urlencode($username));
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首頁</title>
     <!-- 引入 Bootstrap 5 的 CSS 框架 -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=1.3">
</head>
<body>
<div class="top-bar">
        <div class="logo">wuo chenn</div>
        </div>
    <!-- 導 航欄 -->
    <!-- ========== 導覽列區域 ========== -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-#8E8E8E">
        <div class="container-fluid">
            <!-- 網站標題 -->
            <a class="navbar-brand" href="#"></a>
            <!-- 響應式選單按鈕 -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- 導覽選單 -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">            
                    <li class="nav-item"><a class="nav-link " href="首頁.php?id=0">首頁</a></li>
                   
                    
                </ul>
                
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navbar-nav">
                <ul class="navbar-nav">            
                    <li class="nav-item"><a class="nav-link" href="logout.php" id="logoutBtn">登出</a></li>
                </ul>
            </div>            
        </div>
    </nav>
	</div><!-- 主要內容區 -->
	<div class="container">
        <div class="content-box">
           <?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
}
    // 顯示餐廳內容
    try {
        $stmt = $pdo->prepare("SELECT name, image_path, description FROM restaurants WHERE id = :id");
        $stmt->execute(['id' => $id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='restaurant'>";
            echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
            echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['name']) . "'><br><br>";
            echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
            echo "</div>";
        } else {
            echo "<p>查無此餐廳資料</p>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<p>資料庫錯誤：" . $e->getMessage() . "</p>";
        exit;
    }

    // ========== 留言表單 + 顯示留言 區塊 ==========
$currentUser = isset($_SESSION['username']) ? $_SESSION['username'] : '';

echo "<div class='comment-box' style='margin-top:30px;'>";

// 🔹 留言表單
if ($currentUser) {
    echo "<p><strong>留言者：</strong> " . htmlspecialchars($currentUser) . "</p>";
    echo "<form method='POST' style='margin-bottom:15px;'>";
    echo "<textarea name='content' rows='4' cols='60' placeholder='留下你的想法...' required style='font-size:16px;'></textarea><br>";
    echo "<button type='submit' name='submit_comment' style='margin-top:5px; padding:6px 12px;'>送出留言</button>";
    echo "</form><hr>";
} else {
    echo "<p style='color:red;'>⚠️ 你尚未登入，請先登入後才能留言。</p><hr>";
}

// 🔹 顯示留言
$stmt = $pdo->prepare("SELECT username, content, created_at FROM comments WHERE restaurant_id = :id ORDER BY created_at DESC");
$stmt->execute(['id' => $id]);

if ($stmt->rowCount() > 0) {
    echo "<h3 style='margin-bottom:15px;'>留言區 🗨️</h3>";
    while ($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='comment-item' style='border-bottom: 1px solid #ccc; padding: 10px 0;'>";
        echo "<strong>" . htmlspecialchars($comment['username']) . "</strong> 於 " . $comment['created_at'] . " 留言：<br>";
        echo "<p style='margin-top: 5px;'>" . nl2br(htmlspecialchars($comment['content'])) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>目前還沒有留言，快來搶頭香！</p>";
}

echo "</div>";

// 🔹 送出留言處理（一定要在最上面執行 header，或放在檔案最上面！）
if (isset($_POST['submit_comment']) && $currentUser) {
    $username = $currentUser; // 使用登入者名稱
    $content = $_POST['content'];

    $stmt = $pdo->prepare("INSERT INTO comments (restaurant_id, username, content) VALUES (:rid, :user, :content)");
    $stmt->execute(['rid' => $id, 'user' => $username, 'content' => $content]);

    // 重新整理頁面，避免重複送出表單
    header("Location: " . $_SERVER['REQUEST_URI'] . "&comment=success");
    exit;
}

// 🔹 SweetAlert 提示（可選）
if (isset($_GET['comment']) && $_GET['comment'] === 'success') {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: '留言成功！',
            text: '感謝你的回應。'
        }).then(() => {
            const url = new URL(window.location.href);
            url.searchParams.delete('comment');
            window.location.href = url.toString();
        });
    </script>";
}
?>


        </div>
	</div>
    <audio id="bgm" loop>
        <source src="music.mp3" type="audio/mpeg">
        您的瀏覽器不支援 audio 標籤。
    </audio>

    <!-- 音樂控制按鈕 -->
    <button class="bgm-btn" onclick="toggleMusic()">🎵 音樂播放/暫停</button>

        </div>
	</div>

    <script>
        function toggleMusic() {
            const audio = document.getElementById('bgm');
            if (audio.paused) {
                audio.play();
            } else {
                audio.pause();
            }
        }
    </script>
	<footer>
    <div class="footer-container">
        <div class="footer-logo">wuo chenn</div>
        <nav class="footer-links">
            <p style="text-align:center;">早安午安晚安你好嗨嗨哈囉</p>
		</nav>
        <p class="footer-copyright">2025 wuo chenn 版權所有。使用此網站即表示您同意我們的網頁很棒及喜歡我。</p>
    </div>
</footer>
</body>
</html>

        
   