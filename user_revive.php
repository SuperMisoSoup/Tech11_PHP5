<?php
session_start();

//1. POSTデータ取得
$id = $_GET["id"];

//2. DB接続します
include("funcs.php");
$pdo = db_conn();
sschk();

//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE avails_user_table SET life_flg = 1 WHERE id = :id"); // プレースホルダを=で囲む
$stmt->bindValue(':id', $id, PDO::PARAM_INT); // バインド変数に値をバインド
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  sql_error($stmt);
}else{
  redirect("user_select.php");
}
?>