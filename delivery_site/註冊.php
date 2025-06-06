<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>註冊</title>
</head>
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

    button, input[type="submit"] {
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
    }

    button:hover, input[type="submit"]:hover {
        background-color: #D1C5A2;
    }
</style>
<body>

<div class="container">
    <h2>註冊新帳號</h2>
    <form action="register.php" method="post">
        使用者名稱：<input type="text" name="username" required><br><br>
        密碼：<input type="password" name="password" required><br><br>
        確認密碼：<input type="password" name="confirm_password" required><br><br>
        <input type="submit" value="註冊">
    </form>
</div>

</body>
</html>
