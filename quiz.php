<!-- quiz.php -->

<?php
  if(isset($_POST['answer'])){
    if($_POST['answer'] !== NULL and
        $_POST['answer'] === 'プーちゃん' or
        $_POST['answer'] === 'ぷーちゃん' or
        $_POST['answer'] === 'pu-tyan' or
        $_POST['answer'] === 'pu-chan'){
          echo "正解！<br />\n";
        } else{
          echo "不正解！<br />\n";
        }
  }
  echo '購買で売っている大学生協の緑茶の名前はなんでしょう？'
?>

<html>
  <head>
    <script type="text/javascript">
      <!--
      function check() {
        if(document.form1.answer.value==""){
          alert("答えを入力してください。");
          return false;
        }else{
          return true;
        }
      }
      // -->
    </script>
  </head>

  <body>
    <form name="form1" action="quiz.php" method="post" onsubmit="return check()">
      <input type="text" name="answer" onblur="blank_alert()"/>
      <input type="submit"/>
    </form>
  </body>
</html>
