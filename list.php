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
コンテンツ開発　工事一覧
<hr>
[<a href="list.php">TOP</a>]　[<a href="form.html">新規登録</a>]
<br>
<form name="form1" method="post" action="list.php">
案件名：<input type="text" name="search_key"><input type="submit" value="検索する">
</form>

<?php
require_once("DBconnect.php");
$pdo = db_connect();
$prePhpStr = null;

// 削除処理
if(isset($_GET['action']) && $_GET['action'] == 'delete' && $_GET['id'] > 0 ){
    try {
      $pdo->beginTransaction();
      $id = $_GET['id'];
      $sql = "DELETE FROM sch_list WHERE id = :id";
      $stmh = $pdo->prepare($sql);
      $stmh->bindValue(':id', $id, PDO::PARAM_INT );
      $stmh->execute();
      $pdo->commit();
      print "データを" . $stmh->rowCount() . "件、削除しました。<br>";

    } catch (PDOException $Exception) {
      $pdo->rollBack();
      print "エラー：" . $Exception->getMessage();
    }
}

// 挿入処理
if(isset($_POST['action']) && $_POST['action'] == 'insert'){
    try {
      $pdo->beginTransaction();
      $sql = "INSERT  INTO sch_list (pj_name, contact_name, s_date) VALUES ( :pj_name, :contact_name, :s_date )";
      $stmh = $pdo->prepare($sql);
      $stmh->bindValue(':pj_name',  $_POST['pj_name'],  PDO::PARAM_STR );
      $stmh->bindValue(':contact_name', $_POST['contact_name'], PDO::PARAM_STR );
      $stmh->bindValue(':s_date',     $_POST['s_date'],     PDO::PARAM_INT );
      $stmh->execute();
      $pdo->commit();
      print "工事予定を" . $stmh->rowCount() . "件登録しました。<br>";

    } catch (PDOException $Exception) {
      $pdo->rollBack();
      print "エラー：" . $Exception->getMessage();
    }
}


// 更新処理
if(isset($_POST['action']) && $_POST['action'] == 'update'){
    // セッション変数よりidを受け取ります
    $id = $_SESSION['id'];
    
    try {
      $pdo->beginTransaction();
      $sql = "UPDATE  sch_list
                SET 
                  pj_name  = :pj_name,
                  contact_name = :contact_name,
                  s_date        = :s_date
                WHERE id = :id";
      $stmh = $pdo->prepare($sql);
      $stmh->bindValue(':pj_name',  $_POST['pj_name'],  PDO::PARAM_STR );
      $stmh->bindValue(':contact_name', $_POST['contact_name'], PDO::PARAM_STR );
      $stmh->bindValue(':s_date',        $_POST['s_date'],        PDO::PARAM_INT );
      $stmh->bindValue(':id',         $id,                  PDO::PARAM_INT );
      $stmh->execute();
      $pdo->commit();
      print "工事予定を" . $stmh->rowCount() . "件、更新しました。<br>";

    } catch (PDOException $Exception) {
      $pdo->rollBack();
      print "エラー：" . $Exception->getMessage();
    }	
    // 使用したセッション変数を削除する
    unset($_SESSION['id']);
}


// 検索および現在の全データを表示します
try {
  if(isset($_POST['search_key']) && $_POST['search_key'] != ""){
    $search_key = '%' . $_POST['search_key'] . '%'; 
    $sql= "SELECT * FROM sch_list WHERE pj_name  like :pj_name OR contact_name  like :contact_name ";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(':pj_name',  $search_key, PDO::PARAM_STR );
    $stmh->bindValue(':contact_name', $search_key, PDO::PARAM_STR );
    $stmh->execute();
    $count = $stmh->rowCount();
    $prePhpStr = "検索結果は" . $count . "件です。<br><br>";
    print $prePhpStr;
  }else{
    $sql= "SELECT * FROM sch_list ";
    $stmh = $pdo->query($sql);
    $count = $stmh->rowCount();
    if($count == 0){
      print"工事予定はありません";
    }
  }
  $count = $stmh->rowCount();


} catch (PDOException $Exception) {
  print "エラー：" . $Exception->getMessage();
}


if($count < 1){

}else{
?>

<table border="1">
<tbody>
<tr><th>番号</th><th>案件名</th><th>担当名</th><th>日付</th><th>&nbsp;</th><th>&nbsp;</th></tr>
<?php
  while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
?>
<tr>
<td><?=htmlspecialchars($row['id'], ENT_QUOTES)?></td>
<td><?=htmlspecialchars($row['pj_name'], ENT_QUOTES)?></td>
<td><?=htmlspecialchars($row['contact_name'], ENT_QUOTES)?></td>
<td><?=htmlspecialchars($row['s_date'], ENT_QUOTES)?></td>
<td><a href=updateform.php?id=<?=htmlspecialchars($row['id'], ENT_QUOTES)?>>更新</a></td>
<td><a href=list.php?action=delete&id=<?=htmlspecialchars($row['id'], ENT_QUOTES)?>>削除</a></td>
</tr>
<?php
}    
?>
</tbody></table>

<?php
}
?>

</body>
</html>