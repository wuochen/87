<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>速食的介紹</title>
    <style>
        body {
            background-color: #F5F5DC;
            color: #333;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }

        .container {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        button {
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        button:hover {
            background-color:rgb(51, 135, 122);
        }

        /* 新增的左上角返回按鈕樣式 */
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            z-index: 999;
        }

        .back-button:hover {
            background-color: #D1C5A2;
        }
    </style>
</head>

<body>

    <!-- 左上角返回鍵 -->
    <button class="back-button" onclick="history.back()">← 返回</button>

    <div class="container">
        <h2>使用者登入</h2>
        <form action="login.php" method="post">
            使用者名稱: <input type="text" name="username" required><br>
            密碼: <input type="password" name="password" required><br>
            <input type="submit" value="登入"><br>
            <p>還沒有帳號？<a class="nav-link" href="註冊.php">點我註冊</a></p>
        </form>
    </div>

</body>
</html>
