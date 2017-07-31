<!-- 検索結果表示部分 -->
<?php
$htmlBody = <<<END
<div class="back2">
<h2 class="demo-section-title">再検索</h2>
<form method="GET" action="search_result.php">
  <div>
    キーワード：<input type="search" id="q" name="q" placeholder="キーワードを入力..." class="form-control input-sm">
  </div>
  <div>
    検索本数　：<input type="number" id="maxResults" name="maxResults" min="1" max="50" step="1" value="25" class="form-control input-sm">
  </div>
  <div>
  ソート　　：
    <select class="btn btn-default" name="option">
      <option value="date">更新日時順</option>
      <option value="rating">評価の高い順</option>
      <option value="relevance">関連性が高い順</option>
      <option value="title">アルファベット順</option>
      <option value="videoCount">アップロード動画の番号(降)順</option>
      <option value="viewCount">再生回数順</option>
    </select>
  </div>
  <input class="btn btn-primary" type="submit" value="検索">
</form>
</div><br/>
END;

// テキストボックスに打ち込まれたキーワードから検索する
if ($_GET['q'] && $_GET['maxResults']) {

// ライブラリまでのパスを指定して呼び出す
// ローカル用
// require_once 'C:\xampp\htdocs\YouTube\Google\autoload.php';
// require_once 'C:\xampp\htdocs\YouTube\Google\Client.php';
// require_once 'C:\xampp\htdocs\YouTube\Google\Service.php';

// サーバー用
require_once '/var/www/html/YouTube/Google/autoload.php';
require_once '/var/www/html/YouTube/Google/Client.php';
require_once '/var/www/html/YouTube/Google/Service.php';

  // APIキー
  $DEVELOPER_KEY = '***';

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  // 全てのAPIリクエストを作成するためのオブジェクトを定義
  $youtube = new Google_Service_YouTube($client);

  try {
    // listSearchメソッドを呼び出して検索する
    // 検索条件に合わせて処理を変更
    switch ($_GET['option']) {
      // 更新日時順
      case "date":
        $searchResponse = $youtube->search->listSearch('id,snippet',array(
          'q' => $_GET['q'],
          'maxResults' => $_GET['maxResults'],
          'regionCode' => 'jp',
          'order' => 'date'
        ));
        break;
      // 評価の高い順
      case "rating":
        $searchResponse = $youtube->search->listSearch('id,snippet',array(
          'q' => $_GET['q'],
          'maxResults' => $_GET['maxResults'],
          'regionCode' => 'jp',
          'order' => 'rating'
        ));
        break;
      // 関連性が高い順
      case 'relevance':
        $searchResponse = $youtube->search->listSearch('id,snippet',array(
          'q' => $_GET['q'],
          'maxResults' => $_GET['maxResults'],
          'regionCode' => 'jp',
          'order' => 'relevance'
        ));
        break;
      // アルファベット順
      case 'title':
        $searchResponse = $youtube->search->listSearch('id,snippet',array(
          'q' => $_GET['q'],
          'maxResults' => $_GET['maxResults'],
          'regionCode' => 'jp',
          'order' => 'title'
        ));
        break;
      // アップロード動画の番号(降)順
      case 'videoCount':
        $searchResponse = $youtube->search->listSearch('id,snippet',array(
          'q' => $_GET['q'],
          'maxResults' => $_GET['maxResults'],
          'regionCode' => 'jp',
          'order' => 'videoCount'
        ));
        break;
      // 再生回数順
      case 'viewCount':
        $searchResponse = $youtube->search->listSearch('id,snippet',array(
          'q' => $_GET['q'],
          'maxResults' => $_GET['maxResults'],
          'regionCode' => 'jp',
          'order' => 'viewCount'
        ));
        break;
      default:
        $searchResponse = $youtube->search->listSearch('id,snippet',array(
          'q' => $_GET['q'],
          'maxResults' => $_GET['maxResults'],
          'regionCode' => 'jp',
          'order' => 'viewCount'
        ));
        break;
    }

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
    <div class="back4">
    <h3 class="demo-panel-title">動画</h3>
    <ul>$videos</ul>
    </div><br/>
    <div class="back4">
    <h3 class="demo-panel-title">チャンネル</h3>
    <ul>$channels</ul>
    </div><br/>
    <div class="back4">
    <h3 class="demo-panel-title">プレイリスト</h3>
    <ul>$playlists</ul>
    </div><br/>
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

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="Flat-UI-master/dist/css/vendor/bootstrap.min.css" rel="stylesheet">
  <link href="Flat-UI-master/dist/css/flat-ui.min.css" rel="stylesheet">
  <link href="Flat-UI-master/docs/assets/css/demo.css" rel="stylesheet">
  <link rel="shortcut icon" href="Flat-UI-master/img/movie.ico">
  <link rel="stylesheet" href="style.css">

</head>
<body marginwidth="50" marginheight="50">
  <h1>検索結果</h1>
  <?=$htmlBody?>
  <form action="home.php" name="" method="">
    <input type="submit" value="TOPへ戻る" class="btn btn-danger"/>
  </form>
</body>
</html>
