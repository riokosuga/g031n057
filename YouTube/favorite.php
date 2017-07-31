<?php
// データベースに接続
$db_user = 'root';        // ユーザー名
$db_pass = 'fxtrg25x';    // パスワード
$db_name = 'youtube';     // データベース名
// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);

// URL用
$url = '';
// メッセージ用
$message = '';
// カウント用
$num = 1;

// データベース操作
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  // お気に入り追加
  if(!empty($_POST['name']) && !empty($_POST['videoId'])){
    // SQLインジェクション処理
    $name = $mysqli->real_escape_string($_POST['name']);
    $videoId = $mysqli->real_escape_string($_POST['videoId']);
    $mysqli->query("insert into `favorite` (`name`, `videoId`) values ('{$name}', '{$videoId}')");

    $message = 'お気に入りに追加しました！';
  }
}

// データベースから降順でスレッドを取得
$result = $mysqli->query("select * from `favorite` order by `id` desc");

 ?>

<html>
  <head>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="Flat-UI-master/dist/css/vendor/bootstrap.min.css" rel="stylesheet">
    <link href="Flat-UI-master/dist/css/flat-ui.min.css" rel="stylesheet">
    <link href="Flat-UI-master/docs/assets/css/demo.css" rel="stylesheet">
    <link rel="shortcut icon" href="Flat-UI-master/img/movie.ico">
    <link rel="stylesheet" href="style.css">

  </head>
  <body marginwidth="50" marginheight="50">
  <style>
    form { margin:0 }
  </style>
    <h1>お気に入り再生リスト</h1>
    <?php echo $message ?>
      <table>
        <tr>
          <th>お気に入りNo.</th>
          <th>再生リスト名</th>
        </tr>
        <?php foreach($result as $row) : ?>
        <?php
          $id = htmlspecialchars($row['id']);
          $name = htmlspecialchars($row['name']);
          $videoId = htmlspecialchars($row['videoId']);
         ?>
         <tr>
           <td aligin="center"><?php echo $id; ?></td>
           <td>
             <form action="favorite_result.php" method="get" name="form<?php echo $num ?>">
               <input type="hidden" name="videoId" value="<?php echo $videoId ?>" />
               <input type="hidden" name="name" value="<?php echo $name ?>" />
               <a href="#" onclick="document.forms.form<?php echo $num ?>.submit()"><?php echo $name ?></a>
             </form>
           </td>
         </tr>
         <?php $num += 1; ?>
       <?php endforeach; ?>
     </table><br/>
    <form action="home.php">
      <input type="submit" value="TOPへ戻る" class="btn btn-danger"/>
    </form>
  </body>
</html>
