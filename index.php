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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <h1>ToDoリスト</h1>
    <p style="position:absolute; right:5px; top:10px"><?=$_SESSION['username']?>さん <a href="logout.php">ログアウト</a></p>
    <div id="task_progress" style="margin-right:20%">タスク進捗
    <?php
        $pdo = new PDO('mysql:host=mysql320.phy.lolipop.lan;
                        dbname=LAA1554150-php;charset=utf8',
                        'LAA1554150',
                        'Pass0330');
        // 現在の進捗率割り出し
        $sql = $pdo -> prepare('SELECT id FROM task WHERE user_id = (SELECT id FROM user WHERE username = ?)');
        $sql -> execute([$_SESSION['username']]);
        $taskall = $sql -> rowCount();
        $sql = $pdo -> prepare('SELECT id FROM task WHERE user_id = (SELECT id FROM user WHERE username = ?) AND state = 1');
        $sql -> execute([$_SESSION['username']]);
        $taskended = $sql -> rowCount();
        // 0除算対策
        if($taskended == 0){
            $progresswidth = 0;
        } else {
            $progresswidth = $taskended / $taskall * 100;
        }
        echo '<span id="progress_num">'.$progresswidth.'%</span>';
        echo '<div id="progress_outline" style="heght: 15px; width: 100%; background-color:lightgray">';
        echo '<div id="progress" style="height: 15px; width: '.$progresswidth.'%; background-color: lightgreen"> </div>';
        echo '</div>';
    ?>
    </div>
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
        <form method="post">
            <input type="hidden" name="search">
            <input type="text" name="task" placeholder="キーワード" <?php if(isset($_POST['search'])){echo "value=".$_POST['task'];} ?>>
            <input type="date" name="date" <?php if(isset($_POST['search'])){echo "value=".$_POST['date'];} ?>>
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
        <table class="task_grid">
            <tr class="task_grid_line">
                <th class="task_grid_state">状態</th>
                <th class="task_grid_task">タスク</th>
                <th class="task_grid_deadline">期限</th>
                <th class="task_grid_priority">優先度</th>
                <th class="task_grid_manage">操作</th>
            </tr>
            <?php
                $pdo = new PDO('mysql:host=mysql320.phy.lolipop.lan;
                        dbname=LAA1554150-php;charset=utf8',
                        'LAA1554150',
                        'Pass0330');
                // フィルタ・検索
                try{
                if(isset($_POST['search'])){
                    // $searchsql : 完全一致
                    // $searchsql2: 部分一致
                    $searchsql = "SELECT * FROM task WHERE user_id = (SELECT id FROM user WHERE username = ?)";
                    $param[] = $_SESSION['username'];
                    $param2[] = $_SESSION['username'];
                    // 検索条件があるものを$searchsql,$searchsql2に追記
                    if(!empty($_POST['task'])){
                        $searchsql .= " AND task = ?";
                        $param[] = $_POST['task'];
                        $searchsql2 = "SELECT * FROM task WHERE user_id = (SELECT id FROM user WHERE username = ?) AND task LIKE ?";
                        $param2[] = "%".$_POST['task']."%";
                    }
                    if(!empty($_POST['date'])){
                        $searchsql .= " AND deadline = ?";
                        $searchsql2 .= " AND deadline = ?";
                        $param[] = $_POST['date'];
                        $param2[] = $_POST['date'];
                    }
                    if(!empty($_POST['priority'])){
                        $searchsql .= " AND priority = ?";
                        $searchsql .= " AND priority = ?";
                        $param[] = $_POST['priority'];
                        $param2[] = $_POST['priority'];
                    }
                    // 完全一致検索
                    $sql = $pdo -> prepare($searchsql);
                    $sql -> execute($param);
                    // 部分一致検索(キーワードのみ)
                    if(!empty($_POST['task'])){
                        $sql = $pdo -> prepare($searchsql2);
                        $sql -> execute($param2);
                    }
                } else {
                    $sql = $pdo -> prepare('SELECT * FROM task WHERE user_id = (SELECT id FROM user WHERE username = ?)');
                    $sql -> execute([$_SESSION['username']]);
                }
                }catch(Exception $e){
                    echo $e;
                }
                foreach($sql as $row){
                    echo '<tr class="task_grid_line">';
                    echo '<td class="task_grid_state"><input type="checkbox" name="'.$row['id'].'" ';
                    if($row['state']){echo 'checked';} echo '></td>';
                    echo '<td class="task_grid_task">'.$row['task'].'</td>';
                    echo '<td class="task_grid_deadline">'.$row['deadline'].'</td>';
                    $priority = ['無','低','中','高'];
                    echo '<td class="task_grid_priority">'.$priority[$row['priority']].'</td>';
                    echo '<td class="task_grid_manage"><a href="task_edit.php?task='.$row['id'].'">編集</a> <a href="task_delete.php?task='.$row['id'].'" onclick="return confirm(\'本当に削除しますか\')">削除</a></td>';
                    echo '</tr>';
                }
            ?>
        </table>
    </div>
    <script>
        //チェックボックスの監視
        $(document).ready(function(){
            $('input[type="checkbox"]').change(function(){
                var checkedName = [];
                $('input[type="checkbox"]:checked').each(function(){
                    checkedName.push($(this).attr('name'));
                });
                // ajaxでtask_toggle.phpにチェックボックスのデータをpost送信
                $.ajax({
                    type: "POST",
                    url: "task_toggle.php",
                    data: { checkedName: checkedName }
                })
                // ajax処理が成功した際に進捗バーの更新
                .then(
                    function (response){
                        if(response != 0){
                            $("#progress").css("width", response);
                            $("#progress_num").html(response);
                        } else{
                            $("#progress").css("width", "0px");
                            $("#progress_num").html("0%");
                        }
                    }
                );
            });
        });
  </script>
</body>
</html>