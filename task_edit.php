<?php
    session_start();
    if(empty($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
?>
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
            $pdo = new PDO('mysql:host=mysql320.phy.lolipop.lan;
                        dbname=LAA1554150-php;charset=utf8',
                        'LAA1554150',
                        'Pass0330');
            $sql = $pdo -> prepare('SELECT username FROM user WHERE id = (SELECT user_id FROM task WHERE id = ?)');
            $sql -> execute([$_GET['task']]);
            $data = $sql -> fetch(PDO::FETCH_ASSOC);
            if($data['username'] == $_SESSION['username']){
                if(isset($_POST['save'])){
                    $sql = $pdo -> prepare('UPDATE task SET task = ?, deadline = ?, priority = ?, state = ?');
                    $sql -> execute([$_POST['task'], $_POST['deadline'], $_POST['priority'], $_POST['state']]);
                    echo 'データを更新しました。<br>';
                }
                $sql = $pdo -> prepare('SELECT * FROM task WHERE id = ?');
                $sql -> execute([$_GET['task']]);
                $data = $sql -> fetch(PDO::FETCH_ASSOC);
                ?>
                    <form method="post">
                        <h2>タスク編集</h2>
                        <p>内容:<input type="text" name="task" value=<?=$data['task']?>></p>
                        <p>期限:<input type="date" name="deadline" value=<?=$data['deadline']?>></p>
                        <p>優先度:
                            <select name="priority">
                                <option <?php if($data['priority']=='3'){echo 'selected';}?> value="3">>高</option>
                                <option <?php if($data['priority']=='2'){echo 'selected';}?> value="2">中</option>
                                <option <?php if($data['priority']=='1'){echo 'selected';}?> value="1">低</option>
                            </select>
                        </p>
                        <p>状態:
                            <select name="state">
                                <option <?php if($data['state']=='1'){echo 'selected';}?> value="1">完了</option>
                                <option <?php if($data['state']=='0'){echo 'selected';}?> value="0">未完了</option>
                            </select>
                        </p>
                        <p><button type="submit" name="save">保存</button> <a href="index.php">キャンセル</a></p>
                    </form>
                <?php
            }
        }catch(Exception $e){
            echo $e;
        }
    ?>
</body>
</html>