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

        header("Location: 店家1.php?id=$id&comment=success&name=" . urlencode($username));
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
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    body { background-color: #FFF8E1; font-family: Arial, sans-serif; color: #333; }
    .navbar { background-color: #FFC107 !important; }
    .container { max-width: 800px; margin: 40px auto; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .bgm-btn { background-color: #FFC107; border: none; color: #fff; padding: 8px 12px; border-radius: 5px; }
    .bgm-btn:hover { background-color: #FFB300; }
    .bgm-btn {position: fixed; bottom: 20px; right: 20px; background: #fff; border: 1px solid #aaa; padding: 10px; border-radius: 8px; cursor: pointer box-shadow: 0 0 5px #ccc;font-size: 14px;}
    .bgm-btn:hover {background-color: #f0e7d5;}
    .container {max-width: 800px;margin: 40px auto;padding: 40px;background: white;border-radius: 12px;box-shadow: 0 0 10px #FFB300;}
footer {
    background: #FFB300;
    color: white;
    text-align: center;
    padding: 20px 10px;
    font-size: 14px;
    margin-top: 40px;
}
.footer-container {
    max-width: 800px;
    margin: 0 auto;
}
.footer-logo {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}

.footer-copyright {
    margin-top: 10px;
    font-size: 12px;
}
</style>
</head>
<body>
<div class="top-bar">
        <div class="logo">wuo chenn</div>
        </div>
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
                    <li class="nav-item"><a class="nav-link active" href="首頁.php?id=0">首頁</a></li>
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
        <header>
    <h1>🍔 歡迎來到速食宇宙！</h1>
    <p>🥤 這裡有你的嘴最想念的味道！</p>
</header>
 
<div class="container">
        <h2>🍟 推薦速食品牌快速導覽</h2>
        <section>
        <div class="brands">
            <div class="brand"><a class="nav-link" href="店面.php?id=1"><h3> 麥當勞</h3></a></div>
            <div class="brand"><a class="nav-link" href="店面.php?id=2"><h3> 肯德基</h3></a></div>
            <div class="brand"><a class="nav-link" href="店面.php?id=3"><h3> 漢堡王</h3></a></div>
            <div class="brand"><a class="nav-link" href="店面.php?id=4"><h3> 摩斯</h3></a></div> 
    
            <div class="brand"><a class="nav-link" href="store2.php"><h3> ➕ 新增店面</h3></a></div> 
        </div>
       </section>
   
</div>
?>
        <div class="content-box">
<section>
        <h2>🗨️ 今天吃什麼？留言推薦一下吧！</h2>
        
    </section>
     <?php
require_once 'db_connect.php'; // 資料庫連線

// 取得餐廳 ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
}
    $currentUser = isset($_SESSION['username']) ? $_SESSION['username'] : '';

echo "<div class='comment-box' style='margin-top:30px;'>";

// 🔹 留言表單
if ($currentUser) {
    echo "<p><strong>留言者：</strong> " . htmlspecialchars($currentUser) . "</p>";
    echo "<form method='POST' style='margin-bottom:15px;'>";
    echo "<textarea name='content' rows='4' cols='60' placeholder='留下你的想法...' required style='font-size:16px;'></textarea><br>";
    echo "<button type='submit' name='submit_comment' style='margin-top:5px; padding:6px 12px;'>送出留言</button>";
    echo "</form><hr>";
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

<audio id="bgm" loop>
        <source src="mp3/music3.mp3" type="audio/mpeg">
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
            <p style="text-align:center;"><s>早安午安晚安你好嗨嗨哈囉</s></p>
		</nav>
        <p class="footer-copyright">2025 wuo chenn 版權所有。使用此網站即表示您同意我們的網頁很棒及喜歡我。</p>
    </div>
</footer>
</body>
</html>

        
   