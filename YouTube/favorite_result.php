<?php
$htmlBody = '';
$htmlBody .= sprintf('<div class="back1">');
$htmlBody .= sprintf('<h2 class="demo-section-title">お気に入り再生リスト</h2>');
$htmlBody .= sprintf('<iframe width="840" height="473" src="https://www.youtube.com/embed/videoseries?list=%s" frameborder="0" allowfullscreen></iframe>',
  $_GET['videoId']);
$htmlBody .= "<br/>";
$htmlBody .= sprintf('<a href="https://www.youtube.com/playlist?list=%s" target="_blank">%s</a>',
  $_GET['videoId'],
  $_GET['name']);
$htmlBody .= "</div>";
 ?>
<html>
  <head>
    <meta charset="utf-8" />
    <title>お気に入り再生リスト</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="Flat-UI-master/dist/css/vendor/bootstrap.min.css" rel="stylesheet">
    <link href="Flat-UI-master/dist/css/flat-ui.min.css" rel="stylesheet">
    <link href="Flat-UI-master/docs/assets/css/demo.css" rel="stylesheet">
    <link rel="shortcut icon" href="Flat-UI-master/img/movie.ico">
    <link rel="stylesheet" href="style.css">
  </head>
  <body marginwidth="50" marginheight="50">
    <h1>お気に入り再生リスト</h1>
    <?=$htmlBody?><br/>
    <form action="favorite.php">
      <input type="submit" value="お気に入りリストへ戻る" class="btn btn-warning"/>
    </form>
    <form action="home.php">
      <input type="submit" value="TOPへ戻る" class="btn btn-danger"/>
    </form>
  </body>
</html>
