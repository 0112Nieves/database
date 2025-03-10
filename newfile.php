<?php
session_start();
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/style.css">
        <title>排球紀錄網站-新賽事</title>
    </head>

    <body>
        <header>
            <nav>
                User : <?php echo htmlspecialchars($username); ?>
            </nav>
        </header>
        <hr>
        <form id="team_name" action="index.php" method="post">
            <label for="home_team">主隊</label>
            <input id="home_team" name="home_team" type="text" required>
            <span id="alert_home"></span>
            <br>
            <label for="away_team">客隊</label>
            <input id="away_team" name="away_team" type="text" required>
            <span id="alert_away"></span>
            <br>
            <label for="set_num">賽制選擇</label>
            <input type="radio" name="set_num" value="three_set" checked>三局兩勝
            <input type="radio" name="set_num" value="five_set">五局三勝
            <br> 
            <p>第一局輪轉站位</p>
            <label for="1">1號位</label>
            <input type="number" name="1" min="0" max="100" required>
            <br>
            <label for="2">2號位</label>
            <input type="number" name="2" min="0" max="100" required>
            <br>
            <label for="3">3號位</label>
            <input type="number" name="3" min="0" max="100" required>
            <br>
            <label for="4">4號位</label>
            <input type="number" name="4" min="0" max="100" required>
            <br>
            <label for="5">5號位</label>
            <input type="number" name="5" min="0" max="100" required>
            <br>
            <label for="6">6號位</label>
            <input type="number" name="6" min="0" max="100" required>
            <br>
            <label for="libero">自由球員</label>
            <input type="number" name="libero" min="0" max="100" required>
            <br>
            <input type="submit" value="submit" class="input-button" name="newfile">
        </form>
        

        <script src="js/main.js"></script>
    </body>
</html>