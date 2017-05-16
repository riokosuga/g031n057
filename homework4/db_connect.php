<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'fxtrg25x'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$result = $mysqli->query('select * from `messages`');
$result_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['message'])) {
    $mysqli->query("insert into `messages` (`name`,`body`) values ('{$_POST['name']}','{$_POST['message']}')");
    $result_message = 'データベースに登録しました！XD';
  } else {
    $result_message = 'メッセージを入力してください...XO';
  }

  if(!empty($POST['del'])){
    $mysqli->query("insert into 'messages' ('body') values ('{$_POST['message']}')");
    $result_message = 'メッセージを削除しました;';
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
        </tr>
        <?php foreach ($result as $row) : ?>
        <tr>
          <td width="5%" align="center"><?php echo $row['id'] ?></td>
          <td width="10%" align="center"><?php echo $row['name'] ?></td>
          <td width="75%"><?php echo $row['body'] ?></td>
          <td width="10%"><?php echo $row['timestamp'] ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
  </body>
</html>
