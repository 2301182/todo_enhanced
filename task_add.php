<?php
    session_start();
    if(empty($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    if(empty($_POST['task'])||empty($_POST['date'])||empty($_POST['priority'])){
        header("Location: index.php");
        exit();
    }
    try{
        $pdo = new PDO('mysql:host=mysql320.phy.lolipop.lan;
                            dbname=LAA1554150-php;charset=utf8',
                            'LAA1554150',
                            'Pass0330');
        $sql = $pdo -> prepare('SELECT id FROM user WHERE username = ?');
        $sql -> execute([$_SESSION['username']]);
        $data = $sql -> fetch(PDO::FETCH_ASSOC);
        $sql = $pdo -> prepare('INSERT INTO task (user_id, task, deadline, priority) VALUES (?, ?, ?, ?)');
        $sql -> execute([$data['id'],$_POST['task'],$_POST['date'],$_POST['priority']]);
        header("Location: index.php");
    } catch(Exception $e){
        echo $e;
    }
?>