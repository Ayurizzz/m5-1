<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
<?php

    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブルを作成
    
        $sql = "CREATE TABLE IF NOT EXISTS m5"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date VARCHAR(30),"
        . "pass TEXT"
        .");";
        $stmt = $pdo->query($sql);
    
     //編集フォームが入力されたとき
    if(!empty($_POST["nn"]) && !empty( $_POST["name"] ) && !empty( $_POST["text"] )){
        $id = $_POST["nn"];
        $nname = $_POST["name"];
        $ntext = $_POST["text"];
        $npass =  $_POST["passward"];
        $date = date("Y年m月d日 H時i分s秒");
        $sql = 'UPDATE m5 SET name=:name, comment=:comment, date=:date, pass=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
        $stmt -> bindParam(':name', $nname, PDO::PARAM_STR);
        $stmt -> bindParam(':comment', $ntext, PDO::PARAM_STR);
        $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
        $stmt-> bindParam(':pass', $npass, PDO::PARAM_STR);
        $stmt -> execute();

//名前とコメントが入力されたとき
    } elseif( !empty( $_POST["name"] ) && !empty( $_POST["text"] ) && !empty( $_POST["passward"] )){
        $sql = $pdo -> prepare("INSERT INTO m5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
        $name =  $_POST["name"];;
        $comment = $_POST["text"];
        $pass = $_POST["passward"];
        $date = date("Y年m月d日 H時i分s秒");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $sql -> execute();
    
//削除番号が入力されたとき
    }elseif(!empty( $_POST["de_number"])  && !empty( $_POST["passward1"])){
        $id = $_POST["de_number"];
        $pass = $_POST["passward1"];
        $sql = 'delete from m5 where id=:id AND pass=:pass';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->execute();  
          
//編集番号が入力されたとき
    }elseif(!empty( $_POST["ed_num"] ) && !empty( $_POST["passward2"] )){
        $id = $_POST["ed_num"];
        $pass = $_POST["passward2"];
        $sql = 'SELECT * from m5 where id=:id AND pass=:pass';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_INT); 
        $stmt->execute(); 
        $results = $stmt->fetchAll();      
        foreach($results as $row){
            $edit = $row['id'];
            $namae = $row['name']; 
            $ncomment = $row['comment'];
            $passward = $row['pass'];
        }
    }

?>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($namae)) echo $namae;?>" >
        <input type="text" name="text" placeholder="コメント" value="<?php if(!empty($ncomment)) echo $ncomment;?>">
        <br>
        <input type="text" name="passward" placeholder="パスワード" value="<?php if(!empty($passward)) echo $passward;?>" >
        <input type="submit" name="submit" >
        
        <input type="hidden" name="nn" value ="<?php if(!empty($edit)) echo $edit;?>">
    </form>
    <br>
    <form action="" method="post">
        <input type="number" name="de_number" placeholder="削除対象番号">
        <input type="text" name="passward1" placeholder="パスワード" value="" >
        <input type="submit" value="削除"> 
    </form>
    
     <form action="" method="post">
        <input type="number" name="ed_num" placeholder="編集対象番号">
        <input type="text" name="passward2" placeholder="パスワード" value="" >
        <input type="submit" value="編集"> 
    </form> 
    
<?php
    $sql = 'SELECT * FROM m5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
        echo "<hr>";
    }
?>
</body>