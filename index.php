<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>ToDoリスト</h1>
    <p style="text-align:right"><?=$_SESSION['username']?>さん <a link="logout.php">ログアウト</a></p>
    <div>
        <h2>タスク追加</h2>
        <form method="post" action="task_add.php">
            <input type="text" name="task" placeholder="タスク内容">
            <input type="date" name="date">
            <select name="priority">
                <option value="3">優先度：高</option>
                <option value="2">優先度：中</option>
                <option value="1">優先度：低</option>
            </select>
            <button type="submit" name="task_add">追加</button>
        </form>
    </div>
    <div>
        <h2>フィルタ/検索</h2>
        <form method="post" action="task_add.php">
            <input type="text" name="task" placeholder="キーワード">
            <input type="date" name="date" value="すべて">
            <select name="priority">
                <option value="0">優先度：全</option>
                <option value="3">優先度：高</option>
                <option value="2">優先度：中</option>
                <option value="1">優先度：低</option>
            </select>
            <button type="submit" name="task_add">適用</button>
        </form>
    </div>
    <div>
        <table>
            <tr>
                <th>状態</th>
                <th>タスク</th>
                <th>期限</th>
                <th>優先度</th>
                <th>操作</th>
            </tr>
            <?php
                $pdo = new PDO('mysql:host=mysql320.phy.lolipop.lan;
                        dbname=LAA1554150-php;charset=utf8',
                        'LAA1554150',
                        'Pass0330');
                $sql = $pdo -> prepare('SELECT * FROM task WHERE user_id = ?');
                $sql -> execute([$_SESSION['user_id']]);
                $result = $sql -> fetch(PDO::FETCH_ASSOC);
                foreach($result as $value){
                    
                }
            ?>
        </table>
    </div>
</body>
</html>