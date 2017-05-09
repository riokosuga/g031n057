<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'fxtrg25x'; // パスワード
$db_name = 'bbs';     // データベース名

// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

$result_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['message'])) {
    $mysqli->query("insert into `messages` (`body`) values ('{$_POST['message']}')");
    $result_message = 'データベースに登録しました！XD';
  } else {
    $result_message = 'メッセージを入力してください...XO';
  }
}
?>

<html>
  <head>
    <meta charset="UTF-8">
  </head>

  <body>
    <form action="" method="post">
      <input type="text" name="message" />
      <input type="submit" />
    </form>
  </body>
</html>
