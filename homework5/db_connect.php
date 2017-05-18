<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'fxtrg25x'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$result = $mysqli->query('select * from `messages`');
$result_message = '';

//データベース操作
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //メッセージの登録
  if (!empty(htmlspecialchars($_POST['message']) and !empty(htmlspecialchars($_POST['name'])))) {
    $message = $mysqli->real_escape_string($_POST['message']);
    $mysqli->query("insert into `messages` (`name`,`body`) values ('{$_POST['name']}','{$_POST['message']}')");
    $result_message = 'データベースに登録しました！XD';
  } else {
    $result_message = '名前，メッセージを入力してください...XO';
  }

  // メッセージの削除
  if (!empty($_POST['del'])) {
    $mysqli->query("delete from `messages` where `id` = {$_POST['del']}");
    $result_message = 'メッセージを削除しました;)';
  }
}

//データベースからレコード取得
$result = $mysqli->query('select * from `messages` order by `id` desc');
?>

<html>
  <head>
    <meta charset="UTF-8">
  </head>

  <body>
    <h1>掲示板</h1>
    <p><?php echo $result_message; ?></p>
    <h2>書き込みフォーム</h2>
    <form action="" method="post">
      名前：<input type="text" name="name" /><br/>
      本文：<input type="text" name="message" />
      <input type="submit" />
    </form>

      <table border="1">
        <caption>書き込み一覧</caption>
        <tr style="background:#BDBDBD">
          <th>番号</th>
          <th>名前</th>
          <th>本文</th>
          <th>投稿時間</th>
          <th>削除ボタン</th>
        </tr>
        <?php foreach ($result as $row) : ?>
        <tr>
          <td width="5%" align="center"><?php echo htmlspecialchars($row['id']) ?></td>
          <td width="10%" align="center"><?php echo htmlspecialchars($row['name']) ?></td>
          <td width="70%"><?php echo htmlspecialchars($row['body']) ?></td>
          <td width="10%"><?php echo htmlspecialchars($row['timestamp']) ?></td>
          <td width="5%">
            <form action="" method="post">
              <input type="hidden" name="del" value="<?php echo $row['id']; ?>" />
              <input type="submit" value="削除" />
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
  </body>
</html>
