<?php
// データベースに接続
$db_user = 'root';      // ユーザー名
$db_pass = '***x';  // パスワード
$db_name = 'youtube';       // データベース名


// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

// DB操作
?>

<!-- 検索結果表示部分 -->
<?php
$htmlBody = <<<END
<form method="GET" action="search_result.php">
  <h2>再検索</h2>
  <div>
    単語検索: <input type="search" id="q" name="q" placeholder="キーワードを入力...">
  </div>
  <div>
    検索個数: <input type="number" id="maxResults" name="maxResults" min="1" max="50" step="1" value="25">
  </div>
  <input type="submit" value="検索">
</form>
END;

// テキストボックスに打ち込まれたキーワードから検索する
if ($_GET['q'] && $_GET['maxResults']) {

// ライブラリまでのパスを指定して呼び出す
// ローカル用
require_once 'C:\xampp\htdocs\YouTube\Google\autoload.php';
require_once 'C:\xampp\htdocs\YouTube\Google\Client.php';
require_once 'C:\xampp\htdocs\YouTube\Google\Service.php';

// サーバー用
// require_once '/var/www/html/YouTube/Google/autoload.php';
// require_once '/var/www/html/YouTube/Google/Client.php';
// require_once '/var/www/html/YouTube/Google/Service.php';

  // APIキー
  $DEVELOPER_KEY = '***';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  // 全てのAPIリクエストを作成するためのオブジェクトを定義
  $youtube = new Google_Service_YouTube($client);

  try {
    // listSearchメソッドを呼び出して検索する
    $searchResponse = $youtube->search->listSearch('id,snippet', array(
      'q' => $_GET['q'],
      'maxResults' => $_GET['maxResults'],
      'regionCode' => 'jp',
      // 'videoCategoryId' => '20',
      // 'order' => 'date'        // 更新日時順
      // 'order' => 'rating'      // 評価の高い順
      // 'order' => 'relevance'   // 関連性が高い順
      // 'order' => 'title'       // アルファベット順
      // 'order' => 'videoCount'  // アップロード動画の番号（降順）
      'order' => 'viewCount'      // 再生回数順
    ));

    $videos = '';     // ビデオ
    $channels = '';   // チャンネル
    $playlists = '';  // 再生リスト

    // 検索結果を上で宣言した変数に格納していく
    foreach ($searchResponse['items'] as $searchResult) {
      switch ($searchResult['id']['kind']) {
        case 'youtube#video':
          $videos .= sprintf('<div><img src="%s"></div><a href="http://www.youtube.com/watch?v=%s" target="_blank">%s</a>',
            $searchResult['snippet']['thumbnails']['default']['url'],
            $searchResult['id']['videoId'],
            $searchResult['snippet']['title']);
          break;
        case 'youtube#channel':
          $channels .= sprintf('<li>%s (%s)</li>',
              $searchResult['snippet']['title'], $searchResult['id']['channelId']);
          break;
        case 'youtube#playlist':
          $playlists .= sprintf('<div><img src="%s"></div><a href="https://www.youtube.com/playlist?list=%s" target="_blank">%s</a>',
            $searchResult['snippet']['thumbnails']['default']['url'],
            $searchResult['id']['playlistId'],
            $searchResult['snippet']['title']);
          break;
      }
    }

    // $htmlBodyに下記の要素を追加
    $htmlBody .= <<<END
    <h3>動画</h3>
    <ul>$videos</ul>
    <h3>チャンネル</h3>
    <ul>$channels</ul>
    <h3>プレイリスト</h3>
    <ul>$playlists</ul>
END;
  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  }
}
 ?>

<html>
<head>
  <title>検索結果</title>
</head>
<body>
  <h1>検索結果</h1>
  <?=$htmlBody?>
  <form action="home.php" name="" method="">
    <input type="submit" value="戻る" />
  </form>
</body>
</html>
