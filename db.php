<?php
    try{
        $pdo = new PDO('mysql:host=mysql320.phy.lolipop.lan;
                        dbname=LAA1554150-php;charset=utf8',
                        'LAA1554150',
                        'Pass0330');
        $sql = $pdo -> prepare('SELECT password FROM user WHERE username = ?');
        $sql->execute([$_POST['username']]);
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        if($result['password'] == $_POST['password']){
            session_start();
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['user_id'] = $result['id'];
            header('Location: ./index.php');
            exit();
        } else {
            echo '認証失敗';
        }
    } catch(PDOException $e){
        echo 'エラー';
    }
?>