<?php
  // データベースに接続
  $db_user = 'root';      // ユーザー名
  $db_pass = 'fxtrg25x';  // パスワード
  $db_name = 'bbs';       // データベース名

  // MySQLに接続
  $mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
  $result = $mysqli->query('select * from `threads`');
  $result_message = '';

  // データベース操作
  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    // スレッド作成
    if(!empty($_POST['name']) and !empty($_POST['pass'])){
      // SQLインジェクション対策
      $name = $mysqli->real_escape_string($_POST['name']);
      $pass = $mysqli->real_escape_string($_POST['pass']);

      $mysqli->query("insert into `threads` (`name`, `password`) values ('{$name}', '{$pass}')");
      $result_message = 'スレッドを作成しました!';
    }
  }

    // データベースから降順でスレッドを取得
    $result = $mysqli->query("select * from `threads` order by `id` desc");

 ?>

<html>
  <head>
    <meta charset="utf-8">
    <title>掲示板</title>
  </head>
  <body>
    <h1>掲示板</h1>

    <!-- データベース操作時、コメント表示 -->
    <?php echo $result_message ?>

    <h2>スレッド作成</h2>
    <!-- 新規スレッド作成 -->
    <form action="" method="post">
      スレッド名：<input type="text" name="name"></td>
      パスワード：<input type="password" name="pass">
      <input type="submit" value="作成" onclick="brank_check()">
    </form>

    <!-- スレッド一覧表示 -->
    <table border="1">
      <caption>スレッド一覧</caption>
      <tr style="background:#BDBDBD">
        <th>スレッドNo.</th>
        <th>スレッド名</th>
        <th>作成時間</th>
        <th>機能</th>
      </tr>
      <?php foreach($result as $row) : ?>
      <tr>
        <td align="center"><?php echo $row['id'] ?></td>
        <td>
          <form action="board.php" method="post">
            <input type="hidden" name="th_id" value="<?php echo $row['id'] ?>">
            <input type="hidden" name="th_name" value="<?php echo $row['name'] ?>">
            <input type="submit" value="<?php echo $row['name']?>" >
          </form>
        </td>
        <td><?php echo $row['timestamp'] ?></td>
        <td>
          <form action="thread_update.php" method="post">
            <input type="hidden" name="upd" value="<?php echo $row['id'] ?>">
            <input type="submit" value="編集" >
          </form>
          <form action="thread_delete.php" method="post">
            <input type="hidden" name="del" value="<?php echo $row['id'] ?>">
            <input type="submit" value="削除" >
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </body>
</html>
