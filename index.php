<?php
//functionsの呼び出し
session_start();
include("functions.php"); //functionsの呼び出し
chkSsid();

//DB接続(エラー処理追加)
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM markers");
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  error_db_info($stmt);  //functionsの呼び出し
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .='<p>';
    $view .= '<a href="map_update_view.php?id='.$result["id"].'">';
    $view .= $result["name"]."/".$result["address"]."/".$result["type"]."/".$result["lat"]."/".$result["lng"]."/"."[".$result["datetime"]."]";
    $view .='</a>';
    $view .=' ';
    $view .= '<a href="map_delete.php?id='.$result["id"].'">';
    $view .= '[削除]';
    $view .='</a>';
    $view .='</p>';
  }
}

//XMLのDL

//php情報の呼び出し
// require("phpsqlajax_dbinfo.php");

// function parseToXML($htmlStr)
// {
// $xmlStr=str_replace('<','&lt;',$htmlStr);
// $xmlStr=str_replace('>','&gt;',$xmlStr);
// $xmlStr=str_replace('"','&quot;',$xmlStr);
// $xmlStr=str_replace("'",'&#39;',$xmlStr);
// $xmlStr=str_replace("&",'&amp;',$xmlStr);
// return $xmlStr;
// }

// // Opens a connection to a MySQL server
// $connection=mysql_connect ('localhost', $username, $password);
// if (!$connection) {
//   die('Not connected : ' . mysql_error());
// }

// // Set the active MySQL database
// $db_selected = mysql_select_db($database, $connection);
// if (!$db_selected) {
//   die ('Can\'t use db : ' . mysql_error());
// }

// // Select all the rows in the markers table
// $query = "SELECT * FROM markers WHERE 1";
// $result = mysql_query($query);
// if (!$result) {
//   die('Invalid query: ' . mysql_error());
// }

// header("Content-type: text/xml");

// // Start XML file, echo parent node
// echo '<markers>';

// // Iterate through the rows, printing XML nodes for each
// while ($row = @mysql_fetch_assoc($result)){
//   // Add to XML document node
//   echo '<marker ';
//   echo 'name="' . parseToXML($row['name']) . '" ';
//   echo 'address="' . parseToXML($row['address']) . '" ';
//   echo 'lat="' . $row['lat'] . '" ';
//   echo 'lng="' . $row['lng'] . '" ';
//   echo 'type="' . $row['type'] . '" ';
//   echo '/>';
// }

// // End XML file
// echo '</markers>';

?>


<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Google Maps</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
      #map {
        height: 600px;
        width:100%;
      }
      html, body {
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <body>
    <!-- Head[Start] -->
<div id="header">
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="map_regist.php">MAP登録</a>
      <?php if($_SESSION["kanri_flg"]=="1"){ ?>  <!--管理者のみ表示させる-->
        <a class="navbar-brand" href="user_index.php">ユーザー登録</a>
        <a class="navbar-brand" href="user_list.php">ユーザー一覧</a>
        <?php } ?>
        <a class="navbar-brand" href="logout.php">ログアウト</a>
      </div></div>
  </nav>

</div>
<!-- Head[End] -->
<!-- Main[Start] -->
<div id="main">
    <div><?=$_SESSION["name"]?>さん、こんにちは</div>
    <legend>＜MAP登録＞</legend>
    <div class="container jumbotron"><?=$view?></div>
    <div id="map"></div>
</div>
<!-- Main[End] -->

    <script>

//ラベルR・Bをつける

      var customLabel = {
        restaurant: {
          label: 'R'
        },
        bar: {
          label: 'B'
        }
      };

        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(-33.863276, 151.207977),
          zoom: 12
        });
        var infoWindow = new google.maps.InfoWindow;

// 静的なXMLファイルの呼び出し

          downloadUrl('https://storage.googleapis.com/mapsdevsite/json/mapmarkers2.xml', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = address
              infowincontent.appendChild(text);
              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
              });

 //イベントリスナー：クリックした時にコンテンツを表示する
               marker.addListener('click', function() {
                 infoWindow.setContent(infowincontent);
                 infoWindow.open(map, marker);
              });
             });
          });
        }


//ファイルを呼び出す関数

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5xwc_7nN4gP-eBeMBX5BmTslm9BoOUao&callback=initMap">
    </script>
  </body>
</html>