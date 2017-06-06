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

  // スレッド削除
  if(!empty($_POST['pass'])){
    //SQLインジェクション処理
    $del = $mysqli->real_escape_string($_POST['del']);
    $pass = $mysqli->real_escape_string($_POST['pass']);

    $mysqli->query("delete from `messages` where `id` = '$del' and `pass` = '$pass'");
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
  <!-- フォーム入力がない場合のアラート -->
  <script type="text/javascript">
  <!--
  function checkForm(){
    if(document.form1.pass.value == ""){
      alert("パスワードを入力して下さい");
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

  <h2>書き込み削除</h2>
  <!-- スレッド削除 -->
  <form name="form1" action="" method="post">
    <?php $del = htmlspecialchars($_POST['del']); ?>
    <input type="hidden" name="del" value="<?php echo $del ?>">
    パスワード：<input type="password" name="pass">
    <input type="submit" value="削除" onclick="checkForm();">
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
        <?php
        $name = htmlspecialchars($row['name']);           // XSS対策
        $body = htmlspecialchars($row['body']);
        $timestamp = htmlspecialchars($row['timestamp']);
        ?>
        <td><?php echo $name ?></td>
        <td><?php echo $body ?></td>
        <td><?php echo $timestamp ?></td>
      </tr>
    <?php endforeach; ?>
  </table><br>

  <form action="board.php" method="post">
    <?php
    $th_id = htmlspecialchars($_POST['th_id']);         // XSS対策
    $th_name = htmlspecialchars($_POST['th_name']);
    ?>
    <input type="hidden" name="th_id" value="<?php echo $_POST['th_id'] ?>">
    <input type="hidden" name="th_name" value="<?php echo $_POST['th_name'] ?>">
    <input type="submit" value="戻る" >
  </form>
</body>
</html>
