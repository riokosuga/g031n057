<?php
// データベースに接続
$db_user = 'root';      // ユーザー名
$db_pass = 'fxtrg25x';  // パスワード
$db_name = 'youtube';       // データベース名


// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

// DB操作
?>

<!-- 検索結果表示部分 -->
<?php
$htmlBody = <<<END
<form method="GET" action="result.php">
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
// This code will execute if the user entered a search query in the form
// and submitted the form. Otherwise, the page displays the form above.
if ($_GET['q'] && $_GET['maxResults']) {
  // Call set_include_path() as needed to point to your client library.

require_once 'C:\xampp\htdocs\YouTube\Google\autoload.php';
require_once 'C:\xampp\htdocs\YouTube\Google\Client.php';
require_once 'C:\xampp\htdocs\YouTube\Google\Service.php';

// require_once '/var/www/html/YouTube/Google/autoload.php';
// require_once '/var/www/html/YouTube/Google/Client.php';
// require_once '/var/www/html/YouTube/Google/Service.php';

  /*
   * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
   * Google Developers Console <https://console.developers.google.com/>
   * Please ensure that you have enabled the YouTube Data API for your project.
   */
  $DEVELOPER_KEY = '**';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  // Define an object that will be used to make all API requests.
  $youtube = new Google_Service_YouTube($client);

  try {
    // Call the search.list method to retrieve results matching the specified
    // query term.
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

    $videos = '';
    $channels = '';
    $playlists = '';

    // Add each result to the appropriate list, and then display the lists of
    // matching videos, channels, and playlists.
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
