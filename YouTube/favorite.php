<?php
// データベースに接続
$db_user = 'root';      // ユーザー名
$db_pass = 'fxtrg25x';  // パスワード
$db_name = 'youtube';       // データベース名


// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

// DB操作
// データベースから降順でスレッドを取得
$result = $mysqli->query("select * from `favorite` order by `fav_id` desc");

?>

<html>
<head>
  <title>お気に入り動画一覧</title>
</head>
<body>
  <h1>お気に入り動画一覧</h1>
  <tr>
    <th></th>
    <td></td>
  </tr>
  <form action="home.php" name="" method="">
    <input type="submit" value="戻る" />
  </form>
</body>
</html>
