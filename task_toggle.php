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
        }catch(Exception $e){
            echo $e;
        }
    } else {
        echo "error";
    }
?>