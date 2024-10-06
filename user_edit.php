<?php
session_start();
$id = $_GET["id"]; //?id~**を受け取る
$UserRoleId = $_SESSION["UserRoleId"];
include("funcs.php");
$pdo = db_conn();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM avails_user_table WHERE id=:id");
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false) {
    sql_error($stmt);
}else{
    $row = $stmt->fetch();
}
?>



<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>データ更新</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="user_select.php">ユーザ一覧</a></div>
    </div>
  </nav>
</header>
<!-- Head[End] -->


<!-- Main[Start] -->
<form method="POST" action="user_update.php">
  <div class="jumbotron">
   <fieldset>
    <legend>[編集]</legend>
     <label>名前：<input type="text" name="name" value="<?=$row["name"]?>"></label><br>
     <label>ID：<input type="text" name="lid" value="<?=$row["lid"]?>"></label><br>
     <?php if($UserRoleId=="1"){ ?>
      <label>新パスワード：<input type="text" name="lpw"></label><br>
       <input type="submit" value="送信">
     <?php } ?>
     <input type="hidden" name="id" value="<?=$id?>">
    </fieldset>
  </div>
</form>
<!-- Main[End] -->


</body>
</html>
