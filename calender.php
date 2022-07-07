<?php
    session_start();
    $user_email = $_SESSION['email'];
    if (isset($_SESSION['email'])) {//ログインしているとき
        $msg = 'Hello, ' . htmlspecialchars($user_email, \ENT_QUOTES, 'UTF-8') .  ' &emsp;<a href="logout.php" class="btn">LOGOUT</a>';
        //$link = '<a href="logout.php">ログアウト</a>';
    } else {//ログインしていない時
        $msg = 'You have not logged in. &emsp;<a href="login_form.php" class="btn">LOGIN</a>';
    }
    //APIに送るjsonファイル作成
    $data = array('email'=>$user_email);
    $data_json = json_encode($data);
    echo $data_json;
    //returnのis_reserve_app_schedulesの値をPOSTでschedule_delete.phpに送る必要あり。

    //APIのアドレス
    $end_point = 'http://localhost:8090/calender';
    //jsonファイルの内容
    $parameters = [
        'email'=>$user_email,
    ];
    //jsonファイル作成
    $options = [
        'http' => [
            'header'  => 'Content-type: application/json',
            'method'  => 'POST',
            'content' => json_encode($parameters),
        ],
    ];
    $context  = stream_context_create($options);
    $response = @file_get_contents($end_point, false, $context);

    if($response==400){
        $msg = 'Please try again.';
        $link = '<a href="login_form.php" style="color:#20b2aa;">Back</a>';
    }else{
        echo $response;
    }
    
?>
<h1><?php echo $msg; ?></h1>

<!doctype html>
<html lang="'ja">
    <head>
        <meta charset="UTF-8">
        <title>Calender_Registration</title>
        <meta name="description" content="カレンダー">
        <link rel="stylesheet" href="calender2.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Arima:wght@300;700&display=swap" rel="stylesheet">
    </head>

    <body>
        <script type="text/javascript">


            const week = ["日", "月", "火", "水", "木", "金", "土"];
            const today = new Date();
            // 月末だとずれる可能性があるため、1日固定で取得
            var showDate = new Date(today.getFullYear(), today.getMonth(), 1);

            // 初期表示
            window.onload = function () {
                showProcess(today, calendar);
            };
            // 前の月表示
            function prev(){
                showDate.setMonth(showDate.getMonth() - 1);
                showProcess(showDate);
            }

            // 次の月表示
            function next(){
                showDate.setMonth(showDate.getMonth() + 1);
                showProcess(showDate);
            }

            // 予定登録ページへ移動
            function schedule(){
                location.href = 'schedule_entry.php';
            }

            // ログインページへ移動
            function logout(){
                location.href = 'logout.php';
            }

            // カレンダー表示
            function showProcess(date) {
                var year = date.getFullYear();
                var month = date.getMonth();
                document.querySelector('#header').innerHTML = year + "年 " + (month + 1) + "月";

                var calendar = createProcess(year, month);
                document.querySelector('#calendar').innerHTML = calendar;
            }

            // カレンダー作成
            function createProcess(year, month) {
                // 曜日
                var calendar = "<table><tr class='dayOfWeek'>";
                for (var i = 0; i < week.length; i++) {
                    calendar += "<th>" + week[i] + "</th>";
                }
                calendar += "</tr>";

                var count = 0;
                var startDayOfWeek = new Date(year, month, 1).getDay();
                var endDate = new Date(year, month + 1, 0).getDate();
                var lastMonthEndDate = new Date(year, month, 0).getDate();
                var row = Math.ceil((startDayOfWeek + endDate) / week.length);

                // 1行ずつ設定
                for (var i = 0; i < row; i++) {
                    calendar += "<tr>";
                    // 1colum単位で設定
                    for (var j = 0; j < week.length; j++) {
                        if (i == 0 && j < startDayOfWeek) {
                            // 1行目で1日まで先月の日付を設定
                            calendar += "<td class='disabled'>" + (lastMonthEndDate - startDayOfWeek + j + 1) + "</td>";
                        } else if (count >= endDate) {
                            // 最終行で最終日以降、翌月の日付を設定
                            count++;
                            calendar += "<td class='disabled' >" + (count - endDate) + "</td>";
                        } else {
                            // 当月の日付を曜日に照らし合わせて設定
                            count++;
                            if(year == today.getFullYear()
                              && month == (today.getMonth())
                              && count == today.getDate()){
                                //calendar += "<td class='today'>"  + count + "</td>";
                                calendar += `<td class="today" data-date="${year}/${month+1}/${count}">${count}</td>`
                            } else {
                                //calendar += "<td class='nottoday'>" + count + "</td>";
                                calendar += `<td class="nottoday" data-date="${year}/${month+1}/${count}">${count}</td>`
                            }
                        }
                    }
                    calendar += "</tr>";
                }
                return calendar;
            }


            function postForm(value) {
 
                var form = document.createElement('form');
                var request = document.createElement('input');

                form.method = 'POST';
                form.action = 'schedule.php';

                request.type = 'hidden'; //入力フォームが表示されないように
                request.name = 'day';
                request.value = value;

                form.appendChild(request);
                document.body.appendChild(form);

                form.submit();

            }

            document.addEventListener("click", function(e) {
                if(e.target.classList.contains("today")) {
                    //alert('クリックした日付は' + e.target.dataset.date + 'です')
                    postForm(e.target.dataset.date)
                } else if(e.target.classList.contains("nottoday")){
                    //alert('クリックした日付は' + e.target.dataset.date + 'です')
                    postForm(e.target.dataset.date)
                }
            })
            

        </script>
        <div class="wrapper">
            <!-- xxxx年xx月を表示 -->
            <h1 id="header"></h1>

            <!-- ボタンクリックで月移動 -->
            <div id="next-prev-button">
                <button id="prev" onclick="prev()">‹</button>
                <button id="next" onclick="next()">›</button>
            </div>
        
            <!-- カレンダー -->
            <div id="calendar"></div>
            <div id="innerHTMLtxt"></div>

            <!--　予定追加ボタン　-->
            <div id="schedule_entry">
                <button id="schedule" onclick="schedule()">+</button>
            </div>

        </div>   
        <?php
        $username = "example_user";
        $password = "example_pass";
        $hostname = "db";
        $db = "example_db";
        // データベース接続
        $pdo = new PDO("mysql:host={$hostname};dbname={$db};charset=utf8", $username, $password);

        // SQLを実行して結果を画面に表示
/*        $sql = "DELETE FROM users where mail='test@keio.jp'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "id: {$row["user_id"]}, password: {$row["password"]}<br/>\n";
        }*/
        ?>
    </body>
</html>