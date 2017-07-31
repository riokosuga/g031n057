<html lang="ja">
<head>
  <meta charset="utf-8" />
  <title>YouTubeプレイリスト生成</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="Flat-UI-master/dist/css/vendor/bootstrap.min.css" rel="stylesheet">
  <link href="Flat-UI-master/dist/css/flat-ui.min.css" rel="stylesheet">
  <link href="Flat-UI-master/docs/assets/css/demo.css" rel="stylesheet">
  <link rel="shortcut icon" href="Flat-UI-master/img/movie.ico">
  <link rel="stylesheet" href="style.css">

</head>
<body marginwidth="50" marginheight="50">
  <h1>TOP</h1>

  <div class="back1">
    <h2 class="demo-section-title">YouTubeのプレイリストを作るよ！</h1>
    <form method="GET" action="create_search.php">
      <div>
        キーワード：<input type="search" id="q" name="q" placeholder="キーワードを入力..." class="form-control input-sm" size=30>
      </div>
      <div>
        見たい本数：<input type="number" id="maxResults" name="maxResults" min="1" max="25" step="1" value="5" class="form-control input-sm">
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
      <input class="btn btn-info" type="submit" value="決定！" title="確認画面に移ります！">
    </form>
  </div><br/>

  <div class="back2">
    <h2 class="demo-section-title">ただの検索</h2>
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
      <input class="btn btn-primary" type="submit" value="検索" title="検索検索ぅ！">
    </form>
  </div><br/>

  <div class="back3">
    <h2 class="demo-section-title">お気に入りのプレイリスト</h2>
    <form action="favorite.php" name="" method="">
      <input type="submit" value="移動" class="btn btn-warning" />
    </form>
  </div>
</body>
</html>
