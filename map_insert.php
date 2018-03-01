<?php
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
try {
    $pdo = new PDO("mysql:host=localhost;dbname=gs_db;charset=utf8",'root',''); //ID PASSの順番
}catch (PDOException $e) {
    exit( 'DbConnectエラー:' . $e->getMessage()); //えらー手掛かり
}

//３．データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO markers(id, name, address, type, lat, lng, datetime )
VALUES(NULL, :a1, :a2, :a3, :a4, :a5, sysdate())");
$stmt->bindValue(':a1', $name);
$stmt->bindValue(':a2', $address);
$stmt->bindValue(':a3', $type);
$stmt->bindValue(':a4', $lat);
$stmt->bindValue(':a5', $lng);
//実際に実行
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
  }else{
    //５．index.phpへリダイレクト
    header("Location: index.php");
    exit;
  }
  ?>