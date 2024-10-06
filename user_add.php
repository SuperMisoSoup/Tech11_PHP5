<?php
session_start();
//※htdocsと同じ階層に「includes」を作成してfuncs.phpを入れましょう！
//include "../../includes/funcs.php";
include "funcs.php";
sschk();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>USERデータ登録</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
    <?php echo $_SESSION["name"]; ?>さん
    <nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="home.php">Avails登録</a>
            <a class="navbar-brand" href="user_select.php">ユーザー管理</a>
            <a class="navbar-brand" href="logout.php">ログアウト</a>
        </div>
    </div>
</nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<form method="post" action="user_insert.php">
  <div class="jumbotron">
   <fieldset>
    <legend>ユーザー登録</legend>
     <label>名前：<input type="text" name="name"></label><br>
     <label>Login ID：<input type="text" name="lid"></label><br>
     <label>Login PW<input type="text" name="lpw"></label><br>
     <label>管理FLG：
      管理者<input type="radio" name="UserRoleId" value="1">
      運用者<input type="radio" name="UserRoleId" value="2">
      閲覧者<input type="radio" name="UserRoleId" value="3">
    </label>
    <br>
     <!-- <label>退会FLG：<input type="text" name="life_flg"></label><br> -->
     <input type="submit" value="送信">
    </fieldset>
  </div>
</form>
<!-- Main[End] -->


</body>
</html>
