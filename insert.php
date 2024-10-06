<?php
//エラー表示
ini_set("display_errors", 1);

//1. POSTデータ取得
$tmp_name = $_FILES['excel_file']['tmp_name'];
$file_name = $_FILES['excel_file']['name'];

// ファイルの種類をチェック（セキュリティ対策）
$allowed_ext = array('xlsx', 'xls');
$ext = pathinfo($file_name, PATHINFO_EXTENSION);
if (!in_array($ext, $allowed_ext)) {
    // 許可されていないファイルタイプのとき
    echo '許可されていないファイルタイプです。';
    exit;
}

// ファイルを一時ディレクトリから目的の場所に移動（セキュリティ対策）
$upload_dir = 'uploads/'; // アップロード先のディレクトリ
$upload_file = $upload_dir.basename($file_name);
move_uploaded_file($tmp_name, $upload_file);

// ここからExcelファイルを読み込んでDBにINSERTする処理
include('./vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

//2. DB接続します
include("funcs.php");
$pdo = db_conn();

// Excelファイルを読み込む
$reader = new XlsxReader();
$reader->setReadDataOnly(true); // 読み込みモード
$spreadSheet = $reader->load($upload_file); // エクセルファイル読み込み
$highestRow = $spreadSheet->getActiveSheet()->getHighestDataRow();

$sheetData = $spreadSheet->getActiveSheet()->toArray(); // 全てのデータを配列で取得
$columnNames = $sheetData[1]; // 2行目のカラム名を取得

// DBのカラム名を配列に格納
$dbColumns = ['EntryType', 'WorkType', 'ALID', 'TitleInternalAlias', 'EpisodeTitleInternalAlias', 'SeriesAltID', 'SeriesTitleInternalAlias', 'LicenseType', 'LicenseRightsDescription', 'Start', 'End'];

// DBカラムに一致する列の値を配列に格納
$values = [];
foreach ($sheetData as $rowIndex => $row) {
    if ($rowIndex >= 3) { // 2行目以降の行を処理
        $rowValues = [];
        foreach ($dbColumns as $column) {
            $columnIndex = array_search($column, $columnNames);
            if ($columnIndex !== false) {
                $rowValues[] = $row[$columnIndex];
            }else{
                $rowValues[$column] = null;
            }
        }
        $values[] = $rowValues;
    }
}

date_default_timezone_set('Asia/Tokyo');
$unixTimestampOf1900Jan1 = mktime(0, 0, 0, 1, 1, 1900);

// $dbColumnsに基づいて$valuesの配列を並び替える
foreach ($values as &$row) {
    $row = array_combine($dbColumns, $row);
    $row = array_values($row); // 連想配列を数値インデックスの配列に戻す

    // StartとEndのカラムを日付に変換
    $row[9] = date('Y-m-d H:i:s', $unixTimestampOf1900Jan1 + ($row[9]-2) * 86400);
    $row[10] = date('Y-m-d H:i:s', $unixTimestampOf1900Jan1 + ($row[10]-2) * 86400);
}
// var_dump($values);

//３．データ登録SQL作成
// 登録用
$sql = 'INSERT INTO avails_an_table (FileName, UploadDate, ';
$sql .= implode(', ', $dbColumns);
$sql .= ') VALUES (?, ?, ';
$placeholders = array_fill(0, count($dbColumns), '?');
$sql .= implode(', ', $placeholders);
$sql .= ')';

// 更新用
$sql_update = "UPDATE avails_an_table SET FileName = ?, UploadDate = ?, EntryType = ?, WorkType = ?, ALID = ?, TitleInternalAlias = ?, EpisodeTitleInternalAlias	 = ?, SeriesAltID = ?, SeriesTitleInternalAlias = ?, LicenseType = ?, LicenseRightsDescription = ?, Start = ?, End = ? WHERE ALID = ?";

// 削除用
$sql_delete = "DELETE FROM avails_an_table WHERE ALID = ?";

// 4. データ登録処理
$stmt = $pdo->prepare($sql);
$stmt_update = $pdo->prepare($sql_update);
$stmt_delete = $pdo->prepare($sql_delete);

$pdo->beginTransaction(); // トランザクション開始
$currentDate = date('Y-m-d H:i:s'); // 現在の日時をYYYY-MM-DD HH:II:SS形式で取得
foreach ($values as $row) {
    $alid = $row[2];
    $check_sql = "SELECT COUNT(*) FROM avails_an_table WHERE ALID = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$alid]);
    $count = $check_stmt->fetchColumn();

    // EntryType="Full Delete"の場合は削除
    if ($row[0] === 'Full Delete') {

        if ($count > 0) {
            $stmt_delete->execute([$alid]);
        } else {
            echo $alid;
            echo 'ALIDが見つかりません';
            continue;
        }
        continue; // 次のレコードへ
    } else{
        if ($count > 0) {
            // 重複する場合: 更新処理
            $stmt_update->execute(array_merge([$file_name, $currentDate], $row, [$alid]));
        } else {
            // 重複しない場合: 挿入処理
            $stmt->execute(array_merge([$file_name, $currentDate], $row));
        }
    }
}

$pdo->commit(); // コミット

//４．データ登録処理後
if ($stmt->errorCode() !== '00000') {
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("SQLError:".$error[2]);
    $error_update = $stmt_update->errorInfo();
    exit("SQLError_update:".$error_update[2]);
}else{

//５．home.phpへリダイレクト
    $alert = "<script type='text/javascript'>alert('登録しました');</script>";
    echo $alert;
    header("Location: home.php");
    exit();
}

// 不要なファイルを削除
unlink($upload_file);

?>