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
$OAUTH2_CLIENT_ID = '***';
$OAUTH2_CLIENT_SECRET = '***';

$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$client->setScopes('https://www.googleapis.com/auth/youtube');
$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
    FILTER_SANITIZE_URL);
$client->setRedirectUri($redirect);

// 全てのAPIリクエストを作成するためのオブジェクトを定義
$youtube = new Google_Service_YouTube($client);

if (isset($_GET['code'])) {

  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('The session state did not match.');
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
  try {
    // Oauth2で承認されたユーザーのアカウントに公開設定の再生リストを作成する

    // 1. 再生リストのスニペットを作成
    //    タイトルと説明を設定
    $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
    // 日本時間に変更
    date_default_timezone_set('Asia/Tokyo');
    $playlistSnippet->setTitle(date("Y-m-d H:i:s") . 'に作成されたプレイリスト');
    $playlistSnippet->setDescription('YouTube API v3 を利用して作成したプレイリストです');

    // 2. 再生リストのステータスを定義
    //    public -> 公開設定
    //    private -> 非公開設定
    $playlistStatus = new Google_Service_YouTube_PlaylistStatus();
    $playlistStatus->setPrivacyStatus('public');

    // 3. 再生リストのリソースを定義し、スニペットとステータスをリソースに関連付ける
    $youTubePlaylist = new Google_Service_YouTube_Playlist();
    $youTubePlaylist->setSnippet($playlistSnippet);
    $youTubePlaylist->setStatus($playlistStatus);

    // 4. playlists.insert を呼び出して再生リストを作成
    //    playlistResponse では作成したプレイリストの情報を取得できる
    //    ここでは作成したプレイリストのidを取得している
    $playlistResponse = $youtube->playlists->insert('snippet,status',
        $youTubePlaylist, array());
    $playlistId = $playlistResponse['id'];

    // 5. 再生リストに動画を追加
    //    以下のリソースを定義
    //    setVideoId で前のページから送られてきた動画IDを設定
    //    setKind　で種類を設定(youtube#video -> 動画である)
    foreach((array)$_GET['videoId'] as $value){
      $resourceId = new Google_Service_YouTube_ResourceId();
      $resourceId->setVideoId($value);
      $resourceId->setKind('youtube#video');

      // プレイリスト項目のスニペットを定義
      // 追加するビデオのタイトルとは異なる値を表示する場合は、setTitleを設定
      // 上で取得した playlistId と resourceId をスニペットにも追加
      $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
      $playlistItemSnippet->setTitle('First video in the test playlist');
      $playlistItemSnippet->setPlaylistId($playlistId);
      $playlistItemSnippet->setResourceId($resourceId);

      // playlistItemリソースを作成
      // setSnippetでリソースにスニペットを追加
      // playlistItems.insert を呼び出して再生リストに動画を追加
      $playlistItem = new Google_Service_YouTube_PlaylistItem();
      $playlistItem->setSnippet($playlistItemSnippet);
      $playlistItemResponse = $youtube->playlistItems->insert(
          'snippet,contentDetails', $playlistItem, array());
    }

    // 上で作成した再生リストをページに埋め込み表示
    $htmlBody .= "<h3>作成したプレイリスト</h3><ul>";
    $htmlBody .= sprintf('<iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=%s" frameborder="0" allowfullscreen></iframe>',
      $playlistResponse['id']);

  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
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
?>

<!doctype html>
<html>
<head>
<title>New Playlist</title>
</head>
<body>
  <?=$htmlBody?>
  <br/>
  <form action="home.php" name="" method="">
    <input type="submit" value="戻る" />
  </form>
</body>
</html>
