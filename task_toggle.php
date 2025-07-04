<?php
    session_start();
    if(empty($_SESSION['username'])){
        header("Location: login.php");
        exit();
    }
    if(isset($_POST['checkedName'])){
        $checkedName = $_POST['checkedName'];
        // 処理を実行
        try{
            $pdo = new PDO('mysql:host=mysql320.phy.lolipop.lan;
                            dbname=LAA1554150-php;charset=utf8',
                            'LAA1554150',
                            'Pass0330');
            // ユーザーのタスクの情報を入手
            // 所持タスクのid + 数
            $sql = $pdo -> prepare('SELECT id FROM task WHERE user_id = (SELECT id FROM user WHERE username = ?)');
            $sql -> execute([$_SESSION['username']]);
            $data = $sql -> fetchall(PDO::FETCH_ASSOC);
            $rowcnt = $sql -> rowCount();
            // checkedNameで送られてきたもの=状態が1(完了)のものをforeachで判断し$valのセット
            for($cnt = 0; $cnt < $rowcnt; $cnt++){
                $sql = $pdo -> prepare('UPDATE task SET state = ? WHERE id = ?');
                foreach($checkedName as $checking){
                    if($data[$cnt]['id'] == $checking){
                        $val = 1;
                        break;
                    } else {
                        $val = 0;
                    }
                }
                $sql -> execute([$val,$data[$cnt]['id']]);
            }
            // 最終的な完了タスクの数(進捗記録用)
            $sql = $pdo -> prepare('SELECT id FROM task WHERE user_id = (SELECT id FROM user WHERE username = ?) AND state = 1');
            $sql -> execute([$_SESSION['username']]);
            $statecnt = $sql -> rowCount();
            // 0除算対策
            if($statecnt != 0){
                // 完了/タスク全体数 = 進捗率
                echo ($statecnt/$rowcnt*100)."%";
            }
        }catch(Exception $e){
            echo '<script>alert($e)</script>';
        }
    } else {
        // 達成率0の場合。上記のものだと$_POST['checkedName']を参照できないため処理が不完全。
        $pdo = new PDO('mysql:host=mysql320.phy.lolipop.lan;
                            dbname=LAA1554150-php;charset=utf8',
                            'LAA1554150',
                            'Pass0330');
        $sql = $pdo -> prepare('UPDATE task SET state = 0 WHERE user_id = (SELECT id FROM user WHERE username = ?)');
        $sql -> execute([$_SESSION['username']]);
        echo '0%';
    }
?>