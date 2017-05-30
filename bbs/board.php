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

    // スレッド作成
    if(!empty($_POST['name']) and !empty($_POST['body']) and !empty($_POST['pass'])){
      // SQLインジェクション対策
      $name = $mysqli->real_escape_string($_POST['name']);
      $body = $mysqli->real_escape_string($_POST['body']);
      $pass = $mysqli->real_escape_string($_POST['pass']);
      $th_id = $mysqli->real_escape_string($_POST['th_id']);

      $mysqli->query("insert into `messages` (`name`, `body`, `pass`, `thread_id`) values ('{$name}', '{$body}', '{$pass}', '{$th_id}')");
      $result_message = '書き込みました！';
    }

    //データベースから降順でレコード取得
    $result = $mysqli->query("select * from `messages` where `thread_id` = {$_POST['th_id']} order by `id` desc");
  }

?>

<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $_POST['th_name'] ?></title>
    <!-- フォーム入力がない場合のアラート -->
    <script type="text/javascript">
      <!--
      function checkForm(){
        if(document.form1.name.value == "" || document.form1.body.value == "" || document.form1.pass.value == ""){
          alert("全ての項目を入力して下さい");
          return false;
         }else{
           return true;
          }
        }
      // -->
    </script>
  </head>
  <body>
    <h1>掲示板</h1>

    <!-- データベース操作時、コメント表示 -->
    <?php echo $result_message ?>

    <h2><?php echo $_POST['th_name'] ?></h2>

    <!-- 新規書き込みフォーム -->
    <h3>書き込みフォーム</h3>
    <form name="form1" action="" method="post">
      <input type="hidden" name="th_id" value="<?php echo $_POST['th_id'] ?>">
      名前　　　：<input type="text" name="name"><br>
      本文　　　：<input type="text" name="body"><br>
      パスワード：<input type="password" name="pass">
      <input type="submit" value="送信" onclick="checkForm();">
    </form>

    <!-- 書き込み一覧表示 -->
    <table border="1">
      <caption>書き込み一覧</caption>
      <tr style="background:#BDBDBD">
        <th>名前</th>
        <th>本文</th>
        <th>書き込み時間</th>
        <th>機能</th>
      </tr>
      <?php foreach($result as $row) : ?>
        <tr>
          <td algin="center">
            <?php
              $name = htmlspecialchars($row['name']); // XSS対策
              echo $name;
            ?>
          </td>
          <td>
            <?php
              $body = htmlspecialchars($row['body']); // XSS対策
              echo $body;
            ?>
          </td>
          <td><?php echo $row['timestamp'] ?></td>
          <td>
            <form action="board_update.php" method="post">
              <input type="hidden" name="th_id" value="<?php echo $_POST['th_id'] ?>">
              <input type="hidden" name="th_name" value="<?php echo $_POST['th_name'] ?>">
              <input type="hidden" name="upd" value="<?php echo $row['id'] ?>">
              <input type="submit" value="編集" >
            </form>
            <form action="board_delete.php" method="post">
              <input type="hidden" name="th_id" value="<?php echo $_POST['th_id'] ?>">
              <input type="hidden" name="th_name" value="<?php echo $_POST['th_name'] ?>">
              <input type="hidden" name="del" value="<?php echo $row['id'] ?>">
              <input type="submit" value="削除" >
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table><br>

    <form action="thread.php">
      <input type="submit" value="戻る">
    </form>
  </body>
</html>
