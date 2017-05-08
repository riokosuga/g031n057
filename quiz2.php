<!-- quiz2.php -->

<?php
  if(isset($_POST['answer'])){
    if($_POST['answer'] !== NULL and
        $_POST['answer'] === 'A'){
          echo "正解！<br />\n";
        } else{
          echo "不正解！<br />\n";
        }
  }
  echo '小菅李音の血液型は何型でしょう？';
?>

<html>
  <head>
    <script type="text/javascript">
      <!--
      function check() {
        if(document.form1.answer.value==""){
          alert("答え選択してください。");
          return false;
        }else{
          return true;
        }
      }
      // -->
    </script>
  </head>

  <body>
    <form name="form1" action="quiz2.php" method="post" onsubmit="return check()">
      <input type="radio" name="answer" value="A" />A型<br />
      <input type="radio" name="answer" value="B" />B型<br />
      <input type="radio" name="answer" value="O" />O型<br />
      <input type="radio" name="answer" value="AB" />AB型<br />
      <input type="submit"/>
    </form>
  </body>
</html>
