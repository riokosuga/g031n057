<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'fxtrg25x'; // パスワード
$db_name = 'bbs';     // データベース名
// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$result = $mysqli->query('select * from `messages`');
$result_message = '';
// データベース操作
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // メッセージ登録
  if (!empty($_POST['name']) and !empty($_POST['message']) and !empty($_POST['password'])) {
    // XSS対策
    $name = htmlspecialchars($_POST['name']);
    $message = htmlspecialchars($_POST['message']);
    $password = htmlspecialchars($_POST['password']);

    $mysqli->query("insert into `messages` (`name`,`body`,`pass`) values ('{$name}','{$message}','{$password}')");
    $result_message = 'データベースに登録しました！XD';
  } else {
    $result_message = '名前，本文，パスワードを入力してください...XO';
  }

  // メッセージ削除
  if(!empty($POST['del'])){
    $mysqli->query("delete from `messages` where `id` = {$_POST['del']}");
    $result_message = 'メッセージを削除しました';
  }

  // メッセージ更新
  if(!empty($_POST['upd'])){
    $mysqli->query("update `messages` set `body` = {$_POST['upd_txt']} where `id` = {$_POST['upd']}");
    $result_message = 'メッセージを更新しました';
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

    <?php echo $result_message ?>

    <h2>書き込みフォーム</h2>
    <form action="" method="post">
      名前　　　：<input type="text" name="name" /><br/>
      本文　　　：<input type="text" name="message" /><br/>
      パスワード：<input type="password" name="password" />
      <input type="submit" />
    </form>

      <table border="1">
        <caption>書き込み一覧</caption>
        <tr style="background:#BDBDBD">
          <th>番号</th>
          <th>名前</th>
          <th>本文</th>
          <th>投稿時間</th>
          <th>削除</th>
          <th>更新</th>
        </tr>
        <?php foreach ($result as $row) : ?>
        <tr>
          <td width="" align="center"><?php echo $row['id'] ?></td>
          <td width="" align="center">
            <?php
              $name = htmlspecialchars($row['name']); // XSS対策
              echo $name;
            ?>
          </td>
          <td width="">
            <?php
              $body = htmlspecialchars($row['body']); // XSS対策
              echo $body;
            ?>
          </td>
          <td width=""><?php echo $row['timestamp'] ?></td>
          <td width="">
            <form action="" method="post">
              <input type="hidden" name="del" value="<?php echo $row['id']; ?>" />
              <input type="submit" value="削除" />
            </form>
          </td>
          <td>
            <form action="" method="post">
              <input type="hidden" name="upd" value="<?php echo $row['id']; ?>" />
              <input type="text" name="upd_txt" />
              <input type="submit" value="更新" />
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>

  </body>
</html>
