<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<title>工事スケジュール</title>
</head>
<body>
<hr>
工事予定　更新画面
<hr>
[ <a href="list.php">戻る</a>]
<br>

<?php
require_once("DBconnect.php");
$pdo = db_connect();

if(isset($_GET['id']) && $_GET['id'] > 0){
    $id	= $_GET['id']; 
    $_SESSION['id'] = $id;
}else{
    exit('パラメータが不正です。');
}

try {
  $sql= "SELECT * FROM sch_list WHERE id = :id ";
  $stmh = $pdo->prepare($sql);
  $stmh->bindValue(':id',  $id, PDO::PARAM_INT );
  $stmh->execute();
  $count = $stmh->rowCount();
  
} catch (PDOException $Exception) {
  print "エラー：" . $Exception->getMessage();
}

if($count < 1){
  print "更新データがありません。<br>";
}else{
  $row = $stmh->fetch(PDO::FETCH_ASSOC);  
?>
<form name="form1" method="post" action="list.php">
番号：<?=htmlspecialchars($row['id'], ENT_QUOTES)?><br>
案件名：<input type="text" name="pj_name" value="<?=htmlspecialchars($row['pj_name'], ENT_QUOTES)?>"><br>
担当名：<input type="text" name="contact_name" value="<?=htmlspecialchars($row['contact_name'], ENT_QUOTES)?>"><br>
日付：<input type="date" name="s_date" value="<?=htmlspecialchars($row['s_date'], ENT_QUOTES)?>"><br>
<input type="hidden" name="action" value="update">
<br/>
<input type="submit" value="更新">
</form>
<?php
}
?>
</body>
</html>
