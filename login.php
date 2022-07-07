<?php
session_start();
$email = $_POST['email'];
$password = $_POST['password'];
/*try {
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
}*/
?>

<!doctype html>
<html lang="'ja">
    <head>
        <meta charset="UTF-8">
        <title>Calender_Registration</title>
        <meta name="description" content="Schedule">
        <link rel="stylesheet" href="signup.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Arima:wght@300;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="wrapper">
        <?php
        /*
        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $member = $stmt->fetch(); //ログインしたユーザ情報一覧(id?, username, password, email)
        if($member == NULL){
            //$member_password = null;
            $msg = '<h1>Not a valid user_email.</h1>';
            $link = '<p><a href="login_form.php" style="color:#20b2aa;">Back</a><p>';
        }else{
            $member_password = $member["password"]; //$member_passwordはハッシュ化された値
            if (password_verify($password, $member_password)) {
                //DBのユーザー情報をセッションに保存
                //$_SESSION['id'] = $member['id'];
                $_SESSION['email'] = $member['email'];
                $msg = '<h1>Logged in.</h1>';
                $link = '<p><a href="calender.php" style="color:#20b2aa;">Calender</a></p>';
            } else {
                $msg = '<h1>The user email or password is incorrect.</h1>';
                $link = '<p><a href="login_form.php" style="color:#20b2aa;">Back</a></p>';
            }
        }
        echo $msg;
        echo $link;
*/
/*
        //APIのアドレス
        $end_point = 'http://localhost:8090/login';
        //jsonファイルの内容
        $parameters = [
            'email'=>$email,
            'password'=>$password,
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
        echo $response;
        //echo '<p><a href="login_form.php" style="color:#20b2aa;">Back</a></p>';

        if($response==200){
            $msg = '<h1>Logged in.</h1>';
            $link = '<p><a href="calender.php" style="color:#20b2aa;">Calender</a></p>';
        }else if($response==400){
            $msg = '<h1>The user email or password is incorrect.</h1>';
            $link = '<p><a href="login_form.php" style="color:#20b2aa;">Back</a></p>';
        }
        echo '<h1>'.$msg.'</h1>';
        echo '<h1>'.$link.'</h1>';*/

        //APIに送るjsonファイル作成
        $data = array('email'=>$email,'password'=>$password);
        $data_json = json_encode($data);
        echo $data_json;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8090/login');
        $result=curl_exec($ch);
        $res_json = json_decode($result , true );
        echo $res_json['return1'];
        echo 'RETURN:'.$result;
        curl_close($ch);

        ?>
        </div>
    </body>
</html>

