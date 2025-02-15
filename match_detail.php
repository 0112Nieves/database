<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title>賽事詳情</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .button-container {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .button-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .match-info-container {
            margin-bottom: 20px;
        }
        .match-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .match-info span {
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="button-container">
        <button onclick="window.location.href='match.php'">回到賽事列表</button>
    </div>
    <div class="header-container">
        <h1>賽事詳情</h1>
    </div>
    <div class="match-info-container">
        <div class="match-info">
            <?php
                $servername = "140.122.184.129:3310";
                $username = "team15"; 
                $password = "_ZyahJ6exdPmTduP"; 
                $dbname = "team15"; 
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $match_id = $_GET['match_id'];
                $match_info_query = "SELECT match_name, home_team, away_team, match_result FROM match_info WHERE match_id='$match_id'";
                $match_info_result = $conn->query($match_info_query);
                if ($match_info_result->num_rows > 0) {
                    $match_info = $match_info_result->fetch_assoc();
                    $home_team = $match_info['home_team'];
                    $away_team = $match_info['away_team'];
                    $match_result = $match_info['match_result'];
                    echo "<span>主隊: $home_team</span>";
                    echo "<span>客隊: $away_team</span>";
                    echo "<span>獲勝學校: $match_result</span>";
                } else {
                    echo "<span>無法獲取賽事信息</span>";
                }

                $conn->close();
            ?>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>球員</th>
                <th>發球-成功</th>
                <th>發球-失誤</th>
                <th>發球-得分</th>
                <th>攻擊-成功</th>
                <th>攻擊-失誤</th>
                <th>攻擊-得分</th>
                <th>攔網-擊球</th>
                <th>攔網-失誤</th>
                <th>攔網-得分</th>
                <th>攔網-中洞</th>
                <th>接發-好球</th>
                <th>接發-失誤</th>
                <th>防守-好球</th>
                <th>防守-失誤</th>
                <th>舉球-好球</th>
                <th>舉球-失誤</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $players = [];

                // 獲取比賽中的固定球員名單
                $lineup_query = "SELECT player_one, player_two, player_three, player_four, player_five, player_six, libero FROM lineup_sheet WHERE match_id='$match_id'";
                $lineup_result = $conn->query($lineup_query);
                if ($lineup_result->num_rows > 0) {
                    while($row = $lineup_result->fetch_assoc()) {
                        $players[$row['player_one']] = [];
                        $players[$row['player_two']] = [];
                        $players[$row['player_three']] = [];
                        $players[$row['player_four']] = [];
                        $players[$row['player_five']] = [];
                        $players[$row['player_six']] = [];
                        $players[$row['libero']] = [];
                    }
                }

                // 定義所有需要查詢的動作
                $actions = [
                    'serve' => ['發球-成功' => '成功', '發球-失誤' => '失誤', '發球-得分' => '得分'],
                    'attack' => ['攻擊-成功' => '成功', '攻擊-失誤' => '失誤', '攻擊-得分' => '得分'],
                    'block' => ['攔網-擊球' => '擊球', '攔網-失誤' => '失誤', '攔網-得分' => '得分', '攔網-中洞' => '中洞'],
                    'receive' => ['接發-好球' => '好球', '接發-失誤' => '失誤'],
                    'defense' => ['防守-好球' => '好球', '防守-失誤' => '失誤'],
                    'set_correction' => ['舉球-好球' => '好球', '舉球-失誤' => '失誤']
                ];

                // 查詢每個動作的數據並更新到$players中
                foreach ($actions as $table => $queries) {
                    foreach ($queries as $action => $value) {
                        $query = "SELECT player_number, COUNT(*) AS count FROM $table WHERE match_id='$match_id' AND success_error_score='$value' GROUP BY player_number";
                        if ($table == 'block' || $table == 'set_correction' || $table == 'defense') {
                            $query = "SELECT player_number, COUNT(*) AS count FROM $table WHERE match_id='$match_id' AND success_error='$value' GROUP BY player_number";
                        }
                        $result = $conn->query($query);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $player = $row['player_number'];
                                $count = $row['count'];
                                
                                if (!isset($players[$player])) {
                                    $players[$player] = [];
                                }
                                
                                $players[$player][$action] = $count;
                            }
                        }
                    }
                }

                // 渲染表格
                foreach ($players as $player => $stats) {
                    echo "<tr>";
                    echo "<td>$player</td>";
                    foreach ($actions as $queries) {
                        foreach ($queries as $action => $value) {
                            $count = isset($stats[$action]) ? $stats[$action] : 0;
                            echo "<td>$count</td>";
                        }
                    }
                    echo "</tr>";
                }

                $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
