<?php
    $pdo = new PDO('mysql:host=mysql320.phy.lolipop.lan;
                        dbname=LAA1554150-php;charset=utf8',
                        'LAA1554150',
                        'Pass0330');
    $sql = $pdo -> prepare('DELETE FROM task WHERE id = ?');
    $sql -> execute([$_GET['task']]);
    header('Location: index.php');
?>