<?php
  // データベースに接続
  $db_user = 'root';      // ユーザー名
  $db_pass = 'fxtrg25x';  // パスワード
  $db_name = 'bbs';       // データベース名

  // MySQLに接続
  $mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
  $result = $mysqli->query('select * from `messages`');
  $result_message = '';

  // データベース操作
  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // スレッド削除
    if(!empty($_POST['pass'])){
      $mysqli->query("delete from `messages` where `id` = '{$_POST['del']}' and `pass` = '{$_POST['pass']}'");
      $count = $mysqli->affected_rows;
      if($count == 1){
        header("Location: board_delete_comp.php");
      }else{
        $result_message = 'パスワードが違います';
      }
    }
  }

  // データベースから該当スレッドを取得
  $result = $mysqli->query("select * from `messages` where `id` = {$_POST['del']}");

?>

<html>
  <head>
    <meta charset="utf-8">
    <title>書き込み削除 - 掲示板</title>
  </head>
  <body>
    <h1>掲示板</h1>

    <!-- データベース操作時、コメント表示 -->
    <?php echo $result_message ?>

    <h2>書き込み削除</h2>
    <!-- スレッド削除 -->
    <form action="" method="post">
      <input type="hidden" name="del" value="<?php echo $_POST['del'] ?>">
      パスワード：<input type="password" name="pass">
      <input type="submit" value="削除" onclick="brank_check()">
    </form>

    <!-- 該当の書き込み表示 -->
    <table border="1">
      <caption>該当の書き込み</caption>
      <tr style="background:#BDBDBD">
        <th>名前</th>
        <th>本文</th>
        <th>作成時間</th>
      </tr>
      <?php foreach($result as $row) : ?>
      <tr>
        <td>
          <?php
            $name = htmlspecialchars($row['name']);   // XSS対策
            echo $name;
           ?>
        </td>
        <td>
          <?php
            $body = htmlspecialchars($row['body']);   // XSS対策
            echo $body;
           ?>
        </td>
        <td><?php echo $row['timestamp'] ?></td>
      </tr>
      <?php endforeach; ?>
    </table><br>

    <form action="board.php" method="post">
      <input type="hidden" name="th_id" value="<?php echo $_POST['th_id'] ?>">
      <input type="hidden" name="th_name" value="<?php echo $_POST['th_name'] ?>">
      <input type="submit" value="戻る" >
    </form>
  </body>
</html>
