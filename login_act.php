<?php
//最初にSESSIONを開始！！ココ大事！！
session_start();

//POST値
$lid = $_POST["lid"]; //lid
$lpw = $_POST["lpw"]; //lpw

//1.  DB接続します
include("funcs.php");
$pdo = db_conn();

//2. データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM avails_user_table WHERE lid=:lid"); 
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if($status==false){
    sql_error($stmt);
}

//4. 抽出データ数を取得
$val = $stmt->fetch();         //1レコードだけ取得する方法
//$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()

//5.該当１レコードがあればSESSIONに値を代入
//入力したPasswordと暗号化されたPasswordを比較！[戻り値：true,false]
$pw = password_verify(password: $lpw, hash: $val["lpw"]); //$lpw = password_hash($lpw, PASSWORD_DEFAULT);   //パスワードハッシュ化
if($pw && $val['life_flg']=="1"){
  //Login成功 & 退会してない場合
  $_SESSION["chk_ssid"]  = session_id(); //この画面のSESSION IDを取得して代入
  $_SESSION["name"]      = $val['name']; // サーバの名前に値を渡す
  $_SESSION["UserRoleId"] = $val['UserRoleId']; //サーバの管理フラグに値を渡す
  //Login成功時（home.phpへ）
  redirect("home.php");
}else{
  //Login失敗時(login.phpへ)
  $alert = "<script type='text/javascript'>alert('ログインに失敗しました');</script>";
  echo $alert;
  redirect("login.php");
}

exit();


