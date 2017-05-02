<?php
  if ($_POST['answer'] !== NULL and $_POST['answer'] === 'プーちゃん'){
    echo "正解！<br />";
  }
 ?>

<html>
  <head>
  </head>

  <body>
    <?php echo '生協で売っている緑茶の名前はなんでしょう？'?>
    <form action="quiz.php" method="post">
      <input type="text" name="answer" />
      <input type="submit" />
    </form>
  </body>
</html>
