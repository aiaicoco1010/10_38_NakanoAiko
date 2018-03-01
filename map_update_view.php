<?php
session_start();
include("functions.php"); //functionsの呼び出し
chkSsid();

$id = $_GET["id"];
//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM markers WHERE id=:id");
$stmt ->bindValue(":id" , $id , PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  error_db_info($stmt);  //functionsの呼び出し
}else{
  //Selectデータの数だけ自動でループしてくれる
  $row = $stmt->fetch();
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>MAP更新</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="navbar-header">
    <a class="navbar-brand" href="index.php">MAP一覧</a>
    <?php if($_SESSION["kanri_flg"]=="1"){ ?>  <!--管理者のみ表示させる-->
        <a class="navbar-brand" href="user_index.php">ユーザー登録</a>
        <a class="navbar-brand" href="user_list.php">ユーザー一覧</a>
        <?php } ?>
    <a class="navbar-brand" href="logout.php">ログアウト</a>
    </div>    
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div><?=$_SESSION["name"]?>さん、こんにちは</div>
<form method="post" action="map_update.php">
  <div class="jumbotron">
   <fieldset>
    <legend>＜更新＞</legend>
    <label>名前：<input type="text" name="name" value="<?=$row["name"]?>"></label><br>
     <label>住所：<input type="text" name="address" value="<?=$row["address"]?>"></label><br>
     <label>タイプ：<input type="text" name="type" value="<?=$row["type"]?>"></label><br>
    <label>lat：<input type="text" name="lat" value="<?=$row["lat"]?>"></label><br>
    <label>lng：<input type="text" name="lng" value="<?=$row["lng"]?>"></label><br>
     <input type="submit" value="送信">
     <input type="hidden" name="id" value="<?=$id?>"
    </fieldset>
  </div>
</form>
<!-- Main[End] -->
</body>
</html>
