<?php
include("functions.php"); //functionsの呼び出し

//入力チェック(受信確認処理追加)
if(
  !isset($_POST["name"]) || $_POST["name"]=="" ||
  !isset($_POST["address"]) || $_POST["address"]=="" ||
  !isset($_POST["type"]) || $_POST["type"]=="" ||
  !isset($_POST["lat"]) || $_POST["lat"]=="" ||
  !isset($_POST["lng"]) || $_POST["lng"]==""
){
  exit('ParamError');
}

//フォームのデータ受け取り
$name = $_POST["name"];
$address = $_POST["address"];
$type = $_POST["type"];
$lat = $_POST["lat"];
$lng = $_POST["lng"];

//2. DB接続します(エラー処理追加)
$pdo = db_con();


//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE markers SET name = :name, address = :address, type=:type, lat=:lat, lng=:lng WHERE id=:id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':address', $address, PDO::PARAM_STR);
$stmt->bindValue(':type', $type, PDO::PARAM_STR);
$stmt->bindValue(':lat', $lat, PDO::PARAM_STR);
$stmt->bindValue(':lng', $lng, PDO::PARAM_STR);
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  error_db_info($stmt);  //functionsの呼び出し
}else{
  //５．indexへリダイレクト
  header("Location: index.php");
  exit;
}
?>
