<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    try{
        if(isset($_POST['regist'])){
            $pdo = new PDO('mysql:host=mysql320.phy.lolipop.lan;
                        dbname=LAA1554150-php;charset=utf8',
                        'LAA1554150',
                        'Pass0330');
            $sql = $pdo -> prepare('INSERT INTO user (username,password) VALUES (?,?)');
            $sql -> execute([$_POST['username'],$_POST['password']]);
            echo 'アカウントを新規登録しました。<br>';
        }
    }catch(Exception $e){
        echo $e;
    }
    ?>
    <h1>新規登録</h1>
    <form method="post">
        <input type="hidden" name="regist">
        <p>ユーザー名：<input type="text" name="username" value="user1"></p>
        <p>パスワード：<input type="password" name="password" value="pass1"></p>
        <button type="submit" name="login">登録</button>
    </form>
    <br><a href="login.php">ログインはこちら</a>
</html>