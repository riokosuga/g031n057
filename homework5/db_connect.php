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

    // insert文を実行
    $mysqli->query("insert into `messages` (`name`,`body`,`pass`) values ('{$name}','{$message}','{$password}')");
    $result_message = 'データベースに登録しました！';
  } else {
    $result_message = '名前，本文，パスワードを入力してください';
  }

  // メッセージの削除
  if (!empty($_POST['del']) and !empty($_POST['del_pass'])) {
    // パスワードを取り出す
    $result = $mysqli->query("select `pass` from `messages` where `id` = {$_POST['del']}");
    foreach ($result as $row) {
      // パスワード判定
      if($row['pass'] === $_POST['del_pass']){
        $mysqli->query("delete from `messages` where `id` = {$_POST['del']}");
        $result_message = 'メッセージを削除しました';
      }else{
        $result_message = 'パスワードが違います';
      }
    }
  }else{
    //  $result_message = 'パスワードを入力してください';
  }

  // メッセージの編集
  if(!empty($_POST['upd']) and !empty($_POST['upd_pass'])){
    // パスワードを取り出す
    $result = $mysqli->query("select `pass` from `messages` where `id` = {$_POST['upd']}");
    foreach ($result as $row) {
      // パスワード判定
      if($row['pass'] === $_POST['upd_pass']){
        $upd_txt = htmlspecialchars($_POST['upd_txt']); // XSS対策
        $mysqli->query("update `messages` set body = ('{$upd_txt}') where `id` = {$_POST['upd']}");
        $result_message = 'メッセージを編集しました';
      }else{
        $result_message = 'パスワードが違います';
      }
    }
  }else{
    //$result_message = '本文，パスワードを入力してください';
  }
}

//データベースから降順でレコード取得
$result = $mysqli->query('select * from `messages` order by `id` desc');
?>

<html>
  <head>
    <meta charset="UTF-8">
  </head>

  <body>
    <h1>掲示板</h1>

    <!-- データベース操作時、コメント表示 -->
    <?php echo $result_message ?>

    <!-- 新規書き込みフォーム -->
    <h2>書き込みフォーム</h2>
    <form action="" method="post">
      名前　　　：<input type="text" name="name" /><br/>
      本文　　　：<input type="text" name="message" /><br/>
      パスワード：<input type="password" name="password" />
      <input type="submit" />
    </form>

    <!-- データベース内の要素を表示 -->
    <table border="1">
      <caption>書き込み一覧</caption>
      <tr style="background:#BDBDBD">
        <th>番号</th>
        <th>名前</th>
        <th>本文</th>
        <th>投稿時間</th>
        <th>削除</th>
        <th>編集</th>
      </tr>
      <?php foreach ($result as $row) : ?>
        <tr>
          <td width="5%" align="center"><?php echo $row['id'] ?></td>
          <td width="10%" align="center">
            <?php
              $name = htmlspecialchars($row['name']); // XSS対策
              echo $name;
            ?>
          </td>
          <td width="45%">
            <?php
              $body = htmlspecialchars($row['body']); // XSS対策
              echo $body;
            ?>
          </td>
          <td width="10%"><?php echo $row['timestamp'] ?></td>
          <!-- 削除フォーム -->
          <td width="15%">
            <form action="" method="post">
              <input type="hidden" name="del" value="<?php echo $row['id']; ?>" />
              パスワード：<input type="password" name="del_pass" />
              <input type="submit" value="削除" />
            </form>
          </td>
          <!-- 編集フォーム -->
          <td width="15%">
            <form action="" method="post">
              <input type="hidden" name="upd" value="<?php echo $row['id']; ?>" />
              本文　　　：<input type="text" name="upd_txt" /><br/>
              パスワード：<input type="password" name="upd_pass" />
              <input type="submit" value="編集" />
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>

  </body>
</html>
