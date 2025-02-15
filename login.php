<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title>排球紀錄網站-登入</title>
</head>
<body style="border: 5px solid white">
<?php
session_start();

$servername = "140.122.184.129:3310";
$username = "team15"; 
$password = "_ZyahJ6exdPmTduP"; 
$dbname = "team15"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $check_username_sql = "SELECT * FROM user WHERE username = '$username'";
    $check_result = $conn->query($check_username_sql);
    if ($check_result->num_rows == 0) {
        $_SESSION['message'] = "Username not found.";
        header('Location: login.php');
        exit();
    } else {
        $row = $check_result->fetch_assoc();
        if ($row['user_password'] !== $password) {
            $_SESSION['message'] = "Incorrect password.";
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header('Location: home.php');
            exit();
        }
    }
}

$conn->close();
?>
<?php
if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}
?>
<header>
    <nav style="display: inline-block;">
    <form action="register.php" style="display: inline;">
        <input type="submit" class="input-button" value="註冊" />
    </form>
    <form action="rank.php" style="display: inline;">
        <input type="submit" class="input-button" value="查看排行榜" />
    </form>
    </nav>
</header>
<form id="user" action="login.php" method="post">
    <label for="username">請輸入帳號</label>
    <input id="username" name="username" type="text" required>
    <br>
    <label for="password">請輸入密碼</label>
    <input id="password" name="password" type="password" required>
    <br>
    <input type="submit" class="input-button" value="登入">
</form>
</body>
</html>
