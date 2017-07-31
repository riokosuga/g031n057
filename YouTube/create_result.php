<?php

// ライブラリまでのパスを指定して呼び出す
// ローカル用
// require_once 'C:\xampp\htdocs\YouTube\Google\autoload.php';
// require_once 'C:\xampp\htdocs\YouTube\Google\Client.php';
// require_once 'C:\xampp\htdocs\YouTube\Google\Service.php';

// サーバー用
require_once '/var/www/html/YouTube/Google/autoload.php';
require_once '/var/www/html/YouTube/Google/Client.php';
require_once '/var/www/html/YouTube/Google/Service.php';

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
  // ここを書くとたまにエラーになる
  // if (strval($_SESSION['state']) !== strval($_GET['state'])) {
  //   die('セッションの状態が一致しませんでした。');
  // }

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
    $htmlBody = "";
    $htmlBody .= sprintf('<div class="back4">');
    $htmlBody .= "<h3>作成したプレイリスト</h3>";
    $htmlBody .= sprintf('<iframe width="840" height="473" src="https://www.youtube.com/embed/videoseries?list=%s" frameborder="0" allowfullscreen></iframe>',
      $playlistResponse['id']);
    $htmlBody .= "<br/>";
    $htmlBody .= sprintf('<a href="https://www.youtube.com/playlist?list=%s" target="_blank">%s</a>',
      $playlistResponse['id'],
      $playlistResponse['snippet']['title']);
    $htmlBody .= "</div>";

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
  <div class="back2">
  <h3>認証が必要です</h3>
  <p><a href="$authUrl">ココ</a>から認証してください。<p>
  </div>
END;
}
?>

<!doctype html>
<html>
<head>
<title>作成完了</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="Flat-UI-master/dist/css/vendor/bootstrap.min.css" rel="stylesheet">
<link href="Flat-UI-master/dist/css/flat-ui.min.css" rel="stylesheet">
<link href="Flat-UI-master/docs/assets/css/demo.css" rel="stylesheet">
<link rel="shortcut icon" href="Flat-UI-master/img/movie.ico">
<link rel="stylesheet" href="style.css">

</head>
<body>
  <h1>作成完了</h1>
  <?=$htmlBody?>
  <br/>
  <div class="back3">
    <h2 class="demo-section-title">お気に入り登録</h2>
    <form action="favorite.php" method="POST">
      <input type="submit" value="お気に入り登録" class="btn btn-warning" />
      <?php
      $name = htmlspecialchars($playlistResponse['snippet']['title']);
      $videoId = htmlspecialchars($playlistResponse['id']);
       ?>
      <input type="hidden" name="name" value="<?php echo $name ?>" />
      <input type="hidden" name="videoId"  value="<?php echo $videoId ?>" />
    </form>
  </div></br>
  <form action="home.php" name="" method="">
    <input type="submit" value="TOPへ戻る" class="btn btn-danger"/>
  </form>
</body>
</html>
