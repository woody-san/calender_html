<?php
    session_start();
    $user_email = $_SESSION['email'];
    if (isset($_SESSION['email'])) {//ログインしているとき
        $msg = 'Hello, ' . htmlspecialchars($user_email, \ENT_QUOTES, 'UTF-8') .  ' &emsp;<a href="logout.php" class="btn">LOGOUT</a>';
        //$link = '<a href="logout.php">ログアウト</a>';
    } else {//ログインしていない時
        $msg = 'You have not logged in. &emsp;<a href="login_form.php" class="btn">LOGIN</a>';
    }
?>
<?php
//フォームからの値をそれぞれ変数に代入
//$user_id = $user;
$title = $_POST['title'];
$day = $_POST['day'];

try {
    //DB名、ユーザー名、パスワード
    $username = "example_user";
    $password_db = "example_pass";
    $hostname = "db";
    $db = "example_db";
    // データベース接続
    $pdo = new PDO("mysql:host={$hostname};dbname={$db};charset=utf8", $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //PDOのエラーレポートを表示

} catch (PDOException $e) {
    exit('データベースに接続できませんでした。' . $e->getMessage());
}

$sql = "INSERT INTO schedule(id, title, user_email, day, is_reserve_app_schedule) VALUES (NULL, :title, :user_email, :day, :is_reserve_app_schedule)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':title', $title);
$stmt->bindValue(':day', $day);
$stmt->bindValue(':user_email', $user_email);
$stmt->bindValue(':is_reserve_app_schedule', false);
$stmt->execute();
$msg = 'Schedule has been registered.';
$link = '<a href="calender.php">カレンダーへ</a>';

//APIに送るjsonファイル作成
$data = array('title'=>$title,'day'=>$day,'email'=>$user_email);
$data_json = json_encode($data);
echo $data_json;


//APIのアドレス
$end_point = 'http://localhost:8090/register_schedules';
//jsonファイルの内容
$parameters = [
    'title'=>$title,
    'day'=>$day,
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

if($response==200){
    $msg = 'Schedule has been registered.';
    $link = '<a href="calender.php">カレンダーへ</a>';
}else if($response==400){
    $msg = 'Schedule registration failed. Please try again.';
    $link = '<a href="signup.php" style="color:#20b2aa;">Back</a>';
}
echo '<h1>'.$msg.'</h1>';
echo '<h1>'.$link.'</h1>';


?>
<!doctype html>
<html lang="'ja">
    <head>
        <meta charset="UTF-8">
        <title>Calender_Registration</title>
        <meta name="description" content="Schedule">
        <link rel="stylesheet" href="schedule_entry.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Arima:wght@300;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
            <h1><?php echo $msg; ?></h1><!--メッセージの出力-->
            <p><a href="calender.php" style="color:#20b2aa;">Calender</a></p>

            
        </div>
    </body>
</html>