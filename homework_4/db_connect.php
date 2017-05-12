<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'fxtrg25x'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

$result_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['message'])) {
    $mysqli->query("insert into `messages` (`name`,'message') values ('{$_POST['name']},{$_POST['message']}')");
    $result_message = 'データベースに登録しました！XD';
  } else {
    $result_message = 'メッセージを入力してください...XO';
  }
}

//データベースからレコード取得
//$result = $mysqli->query('select * from 'messages'');
?>

<html>
  <head>
    <meta charset="UTF-8">
  </head>

  <body>
    <form action="" method="post">
      名前：<input type="text" name="name" /><br/>
      本文：<input type="text" name="message" />
      <input type="submit" />
    </form>
    <?php foreach ($result as $row) : ?>
      <p><?php echo $row['body']; ?></p>
    <?php endforeach; ?>
  </body>
</html>
