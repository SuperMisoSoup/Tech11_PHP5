<?php
//0. SESSION開始！！
session_start();
$UserRoleId = $_SESSION["UserRoleId"];

//１．関数群の読み込み
include("funcs.php");
sschk();

//２．データ登録SQL作成
$pdo = db_conn();
$sql = "SELECT * FROM avails_user_table AS u INNER JOIN avails_user_role_table AS ur ON u.UserRoleId = ur.UserRoleId";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

//３．データ表示
$values = "";
if($status==false) {
  sql_error($stmt);
}

//全データ取得
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
$json = json_encode($values,JSON_UNESCAPED_UNICODE);

?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ユーザ一覧</title>
<link rel="stylesheet" href="css/range.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
  <?=$_SESSION["name"]?>さん、こんにちは！
  <?php include("menu.php"); ?>
</header>
<!-- Head[End] -->


<!-- Main[Start] -->
<div>
    <div class="container jumbotron">

      <table>
      <?php foreach($values as $v){ ?>
        <tr>
          <td><?=$v["id"]?></td>
          <?php if($UserRoleId == "1"){ ?>
            <td><a href="user_edit.php?id=<?=$v["id"]?>"><?=$v["name"]?></a></td>
            <td><?=$v["UserRole"]?></td>
            <?php if($v["life_flg"]=="1"){ ?>
              <td><a href="user_delete.php?id=<?=$v["id"]?>">[退職に変更]</a></td>
            <?php }else{ ?>
              <td><a href="user_revive.php?id=<?=$v["id"]?>">[復活させる]</a></td>
            <?php } ?>
          <?php }else{?>
            <td><a href="user_edit.php?id=<?=$v["id"]?>"><?=$v["name"]?></a></td>
          <?php } ?>
        </tr>
      <?php } ?>
      </table>

  </div>
</div>
<!-- Main[End] -->


<script>
  const a = '<?php echo $json; ?>';
  console.log(JSON.parse(a));
</script>
</body>
</html>
