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

    // スレッド名編集
    if(!empty($_POST['name']) and !empty($_POST['pass'])){
      // SQLインジェクション対策
      $id = $mysqli->real_escape_string($_POST['upd']);
      $name = $mysqli->real_escape_string($_POST['name']);
      $pass = $mysqli->real_escape_string($_POST['pass']);

      $mysqli->query("update `threads` set `name` = '{$name}' where `id` = '{$id}' and `password` = '{$pass}'");
      $count = $mysqli->affected_rows;
      if($count == 1){
        header("Location: thread_update_comp.php");
      }else{
        $result_message = 'パスワードが違います';
      }
    }
  }

  // データベースから該当スレッドを取得
  $result = $mysqli->query("select * from `threads` where `id` = {$_POST['upd']}");

?>

<html>
  <head>
    <meta charset="utf-8">
    <title>スレッド編集 - 掲示板</title>
    <!-- フォーム入力がない場合のアラート -->
    <script type="text/javascript">
      <!--
      function checkForm(){
        if(document.form1.name.value == "" || document.form1.pass.value == ""){
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

    <h2>スレッド編集</h2>
    <!-- スレッド編集 -->
    <form name="form1" action="" method="post">
      <input type="hidden" name="upd" value="<?php echo $_POST['upd'] ?>">
      スレッド名：<input type="text" name="name"><br>
      パスワード：<input type="password" name="pass">
      <input type="submit" value="編集" onclick="checkForm();">
    </form>

    <!-- 該当スレッド表示 -->
    <table border="1">
      <caption>該当スレッド</caption>
      <tr style="background:#BDBDBD">
        <th>スレッドNo.</th>
        <th>スレッド名</th>
        <th>作成時間</th>
      </tr>
      <?php foreach($result as $row) : ?>
      <tr>
        <td align="center"><?php echo $row['id'] ?></td>
        <td>
          <?php
            $name = htmlspecialchars($row['name']);   // XSS対策
            echo $name;
           ?>
        </td>
        <td><?php echo $row['timestamp'] ?></td>
      </tr>
      <?php endforeach; ?>
    </table><br>

    <form action="thread.php">
      <input type="submit" value="戻る">
    </form>
  </body>
</html>
