<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<?php
//必要なものを定義

$clock=date("Y-m-d H:i:s");

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS keiziban"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "time DATETIME,"
	. "password TEXT"
	.");";
	$stmt = $pdo->query($sql);

if(empty($_POST["password"]) && !empty($_POST["name"]) && !empty($_POST["comment"])){
  echo "パスワードを入力してください";

}
else if(!empty($_POST["name"])&& !empty($_POST["comment"]) && empty($_POST["edit"]) && empty($_POST["delete"])&& empty($_POST["editNo"])){
	$name2 = $_POST["name"];
	$comment2 = $_POST["comment"]; //好きな名前、好きな言葉は自分で決めること
	$time2 = $clock;
	$password2 = $_POST["password"];

$sql = $pdo -> prepare("INSERT INTO keiziban (name, comment,time,password) VALUES (:name, :comment, :time, :password)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':time',$time, PDO::PARAM_STR);
	$sql -> bindParam(':password',$pass, PDO::PARAM_STR);
	$name = $name2;
	$comment = $comment2; 
	$time = $time2;
	$pass = $password2;
	$sql -> execute();
}

if(!empty($_POST["delete"])){//削除
$id = $_POST["delete"];
$d_pass = $_POST["d_password"];
	$sql = 'delete from keiziban where id=:id AND password=:password';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->bindParam(':password', $d_pass, PDO::PARAM_STR);
	$stmt->execute();
 
}
else{
  echo "パスワードを入力してください";
}

if(!empty($_POST["editb"])){

   if(!empty($_POST["edit"])){//編集選択
	$edit = $_POST["edit"];
        $editpass = $_POST["e_password"];
        //selectで取り出す
        $sql = 'SELECT * FROM keiziban';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        //フォームに名前とコメントを代入
        foreach ($results as $row){
            if($row['id'] == $edit && $row['password'] == $editpass){
                $editname = $row['name'];
                $editcomment = $row['comment'];
                $edpass = $row['password'];
               
            }
        }

  }
}

if(!empty($_POST["editNo"])){//編集実行
	$id = $_POST["editNo"];
	$name = $_POST["name"];
        $comment = $_POST["comment"];
        $pass = $_POST["password"];
	$time = $clock;
	$sql = 'update keiziban set name=:name,comment=:comment,time=:time,password=:password where id=:id AND password=:password';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':time', $time, PDO::PARAM_STR);
	$stmt->bindParam(':password', $pass, PDO::PARAM_STR);
	$stmt->execute();
}
?>
<form action="mission_5-1.php" method="post">
<p>
名前:<input type="text" name="name" size="40" value="<?php if(!empty($_POST['edit'])){echo $editname;}?>">
</p>
<p>
コメント:<input type="text" name="comment" size="40" value="<?php if(!empty($_POST['edit'])){echo $editcomment;}?>">
</p>
<p>
<input type="hidden" name="editNo" size="40" value="<?php if(!empty($_POST['edit'])){echo $edit;}?>">
</p>
<p>
パスワード：<input type="password" name="password" size="40">
<input type="submit" name="send" value="送信">
</p>
<p>
削除指定フォーム：<input type="text" name="delete" size="40">
</p>
<p>
パスワード：<input type="password" name="d_password" size="40">
<input type="submit" name="deleteno" value="削除">
</p>
<p>
編集指定フォーム：<input type="text" name="edit" size="40">
</p>
<p>
パスワード：<input type="password" name="e_password" size="40">
<input type="submit" name="editb" value="編集">
</p>
</form>
<?php
//フォーム下に表示する処理
if(!empty($_POST["name"])&& !empty($_POST["comment"])&& empty($_POST["delete"])&& empty($_POST["edit"]) or !empty($_POST["delete"]) or !empty($_POST["edit"])){
$sql = 'SELECT * FROM keiziban';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].",";
		echo $row['time']."<br>";
	}

}
else{//テーブルが思い通りに作成できているか確認
   echo "文字を入力してください";

}
?>
</form>
</body>
</html>
