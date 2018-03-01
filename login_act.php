<?php
session_start();

//0.外部ファイル読み込み
include("functions.php");

//1.  DB接続します
$pdo = db_con();

//2. データ登録SQL作成
$lid= $_POST["lid"];
$lpw= $_POST["lpw"];
$stmt = $pdo->prepare("SELECT * FROM gs_user_table WHERE lid=:lid AND lpw=:lpw AND life_flg=0");
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);
$res = $stmt->execute();

//3. SQL実行時にエラーがある場合
if($res==false){
    queryError($stmt);
}

//4. 抽出データ数を取得
//$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()
$val = $stmt->fetch(); //fetch=1レコードだけ取得する方法

//5. 該当レコードがあればSESSIONに値を代入 。""←空っぽ　ではなければというif文
if( $val["id"] != "" ){  
  $_SESSION["chk_ssid"]  = session_id();  //ログインした時のidをセッションに預けてページをまたいで認証させる
  $_SESSION["kanri_flg"] = $val['kanri_flg'];  //1=管理者、０＝一般
  $_SESSION["name"]      = $val['name'];  //「中野愛子さんこんにちは！」的なことが可能に
  header("Location: index.php");
}else{
  //logout処理を経由して全画面へ
  header("Location: login.php");
}

exit();
?>

