<?php
// データベースに接続
$db_user = 'root';      // ユーザー名
$db_pass = 'fxtrg25x';  // パスワード
$db_name = 'bbs';       // データベース名
// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

// データベース操作時のメッセージ用
$result_message = '';
// データベース操作
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  // スレッド作成
  if (!empty($_POST['name']) && !empty($_POST['pass'])) {
    //SQLインジェクション処理
    $name = $mysqli->real_escape_string($_POST['name']);
    $pass = $mysqli->real_escape_string($_POST['pass']);
    $mysqli->query("insert into `threads` (`name`, `password`) values ('{$name}', '{$pass}')");

    $result_message = 'スレッドを作成しました！';
  }
}
// データベースから降順でスレッドを取得
$result = $mysqli->query("select * from `threads` order by `id` desc");
?>

<html>
<head>
  <meta charset="utf-8">
  <title>掲示板</title>
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

  <h2>スレッド作成</h2>
  <!-- 新規スレッド作成 -->
  <form name="form1" action="" method="post">
    スレッド名：<input type="text" name="name"></td>
    パスワード：<input type="password" name="pass">
    <input type="submit" value="作成" onclick="checkForm();">
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
        <?php
        $id = htmlspecialchars($row['id']);           // XSS対策
        $name = htmlspecialchars($row['name']);
        $timestamp = htmlspecialchars($row['timestamp']);
        ?>
        <td align="center"><?php echo $id ?></td>
        <td>
          <form action="board.php" method="post">
            <input type="hidden" name="th_id" value="<?php echo $id ?>">
            <input type="hidden" name="th_name" value="<?php echo $name ?>">
            <input type="submit" value="<?php echo $name?>" >
          </form>
        </td>
        <td><?php echo $timestamp ?></td>
        <td>
          <form action="thread_update.php" method="post">
            <input type="hidden" name="update" value="<?php echo $id ?>">
            <input type="submit" value="編集" >
          </form>
          <form action="thread_delete.php" method="post">
            <input type="hidden" name="del" value="<?php echo $id ?>">
            <input type="submit" value="削除" >
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
