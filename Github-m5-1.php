<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>m5-1</title>
</head>
<body>
<?php
    //DBへ接続
    $dsn = データベース名;
    $user = ユーザー名;
    $password = パスワード;
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $pdo->query('SET NAMES utf8;');
    
    date_default_timezone_set('Asia/Tokyo');
    $updated_at = date("Y/m/d/H:i:s");
    
//投稿機能  
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["postNo"]) && !empty($_POST["Newpassword"])){
        
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $Newpassword = $_POST["Newpassword"];
       //DBに書き込む処理 
       $sql = "insert into m5(name,comment,password,updated_at) values(:name,:comment,:Newpassword,:updated_at)";
       $stmt = $pdo -> prepare($sql);
       $stmt->bindParam(':name', $name, PDO::PARAM_STR);
       $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
       $stmt->bindParam(':Newpassword', $Newpassword, PDO::PARAM_STR);
       $stmt->bindParam(':updated_at',$updated_at,PDO::PARAM_STR);
       $stmt->execute();
       
//編集機能
    }elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["postNo"])){
        $rename = $_POST["name"];
        $recomment = $_POST["comment"];
        $postNo = $_POST["postNo"];
        $Newpassword = $_POST["Newpassword"];
        $sql = "UPDATE m5 SET name =:name,comment=:comment,updated_at=:updated_at where id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $postNo, PDO::PARAM_INT);
        $stmt->bindParam(':name', $rename, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $recomment, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at',$updated_at,PDO::PARAM_STR);
        $stmt->execute();
    }


//削除機能
    if(!empty($_POST["deleteNo"]) && !empty($_POST["Dpassword"])){
        $deleteNo = $_POST["deleteNo"];
        $Dpassword = $_POST["Dpassword"];
            
        $sql = "delete from m5 where id=:id and password=:password";
        $stmt = $pdo -> prepare($sql);
        $stmt->bindParam(':id', $deleteNo, PDO::PARAM_INT);
        $stmt->bindParam(':password', $Dpassword, PDO::PARAM_STR);
        $stmt->execute();
    }
//編集対象番号の投稿を投稿フォームに表示
    if(!empty($_POST["editNo"]) && !empty($_POST["Epassword"])){
        $edit = $_POST["editNo"];
        $Epassword = $_POST["Epassword"];
        $sql = "SELECT name,comment FROM m5 where id=:id and password=:password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $edit, PDO::PARAM_INT);
        $stmt->bindParam(':password', $Epassword, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt-> fetchAll();
        foreach ($results as $row) {
            $editname = $row["name"];
            $editcomment = $row["comment"];
        }
    }
?>
<form action="" method ="POST">
    <input type="text" name="name" placeholder="名前" value="<?php if(!empty($edit) && !empty($Epassword)){echo $editname;} ?>">
    <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($edit) && !empty($Epassword)){echo $editcomment;} ?>">
    <input type="hidden" name="postNo" placeholder="投稿番号" value="<?php if(!empty($edit) && !empty($Epassword)) {echo $edit;}?>">
    <input type="text" name="Newpassword" placeholder="パスワード">
    <input type="submit" name="submit" value="送信"><br>
    <input type="number" name="deleteNo" placeholder="削除対象番号">
    <input type="text" name="Dpassword" placeholder="パスワード">
    <input type="submit" name="delete" value="削除"><br>
    <input type="number" name="editNo" placeholder="編集対象番号">
    <input type="text" name="Epassword" placeholder="パスワード">
    <input type="submit" name="edit" value="編集">
</form>
<?php

//表示機能
    $sql = "SELECT * FROM m5";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    // foreach文で繰り返し配列の中身を一行ずつ出力
    foreach ($results as $row) {
        echo $row["id"]." ";
        echo $row["name"]." ";
        echo $row["comment"]." ";
        echo $row["updated_at"]."<br>";
    echo "<hr>";
    }
?>
</body>
</html>