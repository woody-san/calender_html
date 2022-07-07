<!doctype html>
<html lang="'ja">
    <head>
        <meta charset="UTF-8">
        <title>Calender_Login</title>
        <meta name="description" content="カレンダーアプリ">
        <link rel="stylesheet" href="register.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Arima:wght@300;700&display=swap" rel="stylesheet">
    </head>

    <body>
    <div class="wrapper">

        <?php
        //フォームからの値をそれぞれ変数に代入
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        /*
        try {
            //DB名、ユーザー名、パスワード
            $db_username = "example_user";
            $password_db = "example_pass";
            $hostname = "db";
            $db = "example_db";
            // データベース接続
            $pdo = new PDO("mysql:host={$hostname};dbname={$db};charset=utf8", $db_username, $password_db);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //PDOのエラーレポートを表示

        } catch (PDOException $e) {
            exit('データベースに接続できませんでした。' . $e->getMessage());
        }

        $sql = "SELECT * FROM user WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $member = $stmt->fetch();
        if($member == NULL){
            $member_email = null;
            //登録されていなければinsert 
            //$sql = "INSERT INTO users(id, user_id, mail, password) VALUES (NULL, :user_id, :mail, :password)";
            $sql = "INSERT INTO user(username, password, email) VALUES (:username, :password, :email)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $stmt->execute();
            $msg = 'Member registration is complete.';
            $link = '<a href="login_form.php" style="color:#20b2aa;">Login page</a>';
        }else{
            //$member_email = $member["email"];
            $msg = 'This user email address is not available.';
            $link = '<a href="signup.php" style="color:#20b2aa;">Back</a>';
        }

        echo '<h1>'.$msg.'</h1>';
        echo '<h1>'.$link.'</h1>';
            */
        //APIに送るjsonファイル作成
        $data = array('username'=>$username,'password'=>$password,'email'=>$email);
        $data_json = json_encode($data);
        echo $data_json;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'http:/locahost:8090/signup');
        $result=curl_exec($ch);
        $res_json = json_decode($result , true );
        //echo $res_json['return1'];
        echo 'RETURN:'.$result;
        curl_close($ch);
/*
        //APIのアドレス
        $end_point = 'http://localhost:8090/signup';
        //jsonファイルの内容
        $parameters = [
            'username'=>$username,
            'password'=>$password,
            'email'=>$email,
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
            $msg = 'Member registration is complete.';
            $link = '<a href="login_form.php" style="color:#20b2aa;">Login page</a>';
        }else if($response==400){
            $msg = 'User registration failed. Please try again.';
            $link = '<a href="signup.php" style="color:#20b2aa;">Back</a>';
        }
        echo '<h1>'.$msg.'</h1>';
        echo '<h1>'.$link.'</h1>';*/

        ?>

</div>
    </body>
</html>