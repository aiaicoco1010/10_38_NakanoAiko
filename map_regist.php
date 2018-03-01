<?php
//functionsの呼び出し
session_start();
include("functions.php"); //functionsの呼び出し
chkSsid();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>MAP登録</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="index.php">MAP一覧をみる</a></div>
    <div class="navbar-header"><a class="navbar-brand" href="user_index.php">ユーザー登録をする</a></div>
  </div></nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div><?=$_SESSION["name"]?>さん、こんにちは</div>
<form method="post" action="map_insert.php">
  <div class="jumbotron">
   <fieldset>
    <legend>＜MAP登録＞</legend>
     <label>名前：<input type="text" name="name"></label><br>
     <label>住所：<input type="text" name="address"></label><br>
     <label>タイプ：
      <input type="radio" name="type" value="0" checked="checked">restaurant
      <input type="radio" name="type" value="1">bar
    </label><br>
    <label>lat：<input type="text" name="lat"></label><br>
    <label>lng：<input type="text" name="lng"></label><br>
     <input type="submit" value="送信">
    </fieldset>
  </div>
</form>
<!-- Main[End] -->


</body>
</html>
