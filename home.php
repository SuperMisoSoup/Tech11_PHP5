<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Avails登録</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="select.php">データ一覧</a>        
        <!-- 閲覧者にはユーザ管理タブを表示させない -->
        <?php if($_SESSION["UserRoleId"]!=3){ ?>
            <a class="navbar-brand" href="user_select.php">ユーザ管理</a>
          <?php } ?>
        <a class="navbar-brand" href="logout.php">ログアウト</a>
      </div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<?php if($_SESSION["UserRoleId"]!=3){ ?>
<form method="post" action="insert.php" enctype="multipart/form-data">
  <div class="inportarea">
   <fieldset>
    <legend>Availsファイル</legend>
      <input type="file" name="excel_file" accept=".xlsx,.xls">
      <input type="submit" value="送信">
    </fieldset>
  </div>
</form>
<?php } ?>
<!-- Main[End] -->


</body>
</html>
