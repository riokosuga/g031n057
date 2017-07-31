<?php

// ライブラリまでのパスを指定して呼び出す
// ローカル用
require_once 'C:\xampp\htdocs\YouTube\Google\autoload.php';
require_once 'C:\xampp\htdocs\YouTube\Google\Client.php';
require_once 'C:\xampp\htdocs\YouTube\Google\Service.php';

// サーバー用
// require_once '/var/www/html/YouTube/Google/autoload.php';
// require_once '/var/www/html/YouTube/Google/Client.php';
// require_once '/var/www/html/YouTube/Google/Service.php';

// セッション開始
session_start();

// Oauth2のクライアントIDとクライアントシークレット
$OAUTH2_CLIENT_ID = '1020596575942-gl6od90rmjn4cq8965v8bhqf4vjjq9sp.apps.googleusercontent.com';
$OAUTH2_CLIENT_SECRET = 'YLyIDYpzK8x6TX4TwAbIHt1Z';

$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$client->setScopes('https://www.googleapis.com/auth/youtube');
$redirect = filter_var('http://localhost/YouTube/create_search.php', FILTER_SANITIZE_URL);
$client->setRedirectUri($redirect);

// 全てのAPIリクエストを作成するためのオブジェクトを定義
$youtube = new Google_Service_YouTube($client);

if (isset($_GET['code'])) {
  // ここを書くとたまにエラーになる
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('セッションの状態が一致しませんでした。');
  }

  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: ' . $redirect);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

// Check to ensure that the access token was successfully acquired.
if ($client->getAccessToken()) {
  // アクセストークンが切れている場合の処理
  if($client->isAccessTokenExpired()){
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
  }

  $_SESSION['token'] = $client->getAccessToken();
} else {
  // Oauth2を見認証の場合以下が実行される
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;

  $authUrl = $client->createAuthUrl();
  $htmlBody = <<<END
  <h3>認証が必要です</h3>
  <p><a href="$authUrl">ココ</a>から認証してください。<p>
END;
}

header("Location: http://localhost/YouTube/create_search.php");
?>

<!doctype html>
<html>
<head>
<title>OAuth認証</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="Flat-UI-master/dist/css/vendor/bootstrap.min.css" rel="stylesheet">
<link href="Flat-UI-master/dist/css/flat-ui.min.css" rel="stylesheet">
<link href="Flat-UI-master/docs/assets/css/demo.css" rel="stylesheet">
<link rel="shortcut icon" href="Flat-UI-master/img/favicon.ico">

</head>
<body>
  <p>既に認証済みです</p>
  <br/>
  <form action="home.php" name="" method="">
    <input type="submit" value="TOPへ戻る" class="btn btn-warning"/>
  </form>
</body>
</html>
