<?php
// データベースに接続
$db_user = 'root';      // ユーザー名
$db_pass = '***';  // パスワード
$db_name = 'youtube';   // データベース名


// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

// DB操作

// データベースから降順でスレッドを取得
$result = $mysqli->query("select * from `user` order by `id` desc");
?>

<html>
<head>
  <meta charset="utf-8" />
  <title>YouTube動画 自動プレイリスト生成</title>
</head>
<body>
  <h1>YouTubeのプレイリストを作るよ！</h1>
  <form method="GET" action="create_search.php">
    <div>
      関連単語: <input type="search" id="q" name="q" placeholder="キーワードを入力...">
    </div>
    <div>
      プレイリストに含める個数: <input type="number" id="maxResults" name="maxResults" min="1" max="25" step="1" value="5">
    </div>
    <input type="submit" value="検索">
  </form>


  <!-- <form action="" name="" method="get"> -->
    <!-- 再生時間　：<input type="number" name="time" min="5" max="120" step="1" value="5">分 (5~120分)<br> -->
    <!-- キーワード：<input type="text" name="keyword" /><br> -->
    <!-- ジャンル：<input type="" name="genre" /><br> -->
    <!-- <input type="submit" value="作成！" /> -->
  <!-- </form> -->

  <!-- <h2>テスト-再生リストの作成</h2>
  <form action="create_playlist.php" name="" method="">
    <input type="submit" value="作成" />
  </form> -->

  <h2>ただの検索</h2>
  <form method="GET" action="search_result.php">
    <div>
      単語検索: <input type="search" id="q" name="q" placeholder="キーワードを入力...">
    </div>
    <div>
      検索個数: <input type="number" id="maxResults" name="maxResults" min="1" max="50" step="1" value="25">
    </div>
    <input type="submit" value="検索">
  </form>

  <!-- 累計時間表示 -->
  <?php
  // foreach($result as $row){
  //     $minute = htmlspecialchars($row['minute']);     // XSS対策
  // }
  ?>
  <!-- <p><?php echo '今までの合計は'.$minute.'分になりました！'?></p> -->

  <h2>お気に入り動画</h2>
  <form action="favorite.php" name="" method="">
    <input type="submit" value="移動" />
  </form>
</body>
</html>
