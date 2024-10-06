<?php
session_start();

//1. POSTデータ取得
$id = $_GET["id"];

//2. DB接続します
include("funcs.php");
$pdo = db_conn();
sschk();

//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE avails_user_table SET life_flg=0 WHERE id=:id"); //life_flgを0にする
// $stmt = $pdo->prepare("DELETE FROM avails_user_table WHERE id=:id");
// $stmt->bindValue(':id', $id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':id', $id, PDO::PARAM_INT); // バインド変数に値をバインド
$status = $stmt->execute(); //実行

//４．データ登録処理後
if($status==false){
  sql_error($stmt);
}else{
  redirect("user_select.php");
}
?>