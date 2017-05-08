<!-- quiz_reslut.php -->

<html>
  <head>
  </haed>

  <body>
    <?php
      echo '興味のある研究分野は何ですか？ -> [回答]';
      foreach($_POST['ans1'] as $value){
        echo $value.",";
      }

      echo '<br />';

      echo 'あなたの血液型は何ですか？ -> [回答]';
      foreach($_POST['ans2'] as $value){
        echo $value.",";
      }

      echo '<br />';

      echo 'あなたの好きな言語は何ですか？ -> [回答]';
      foreach($_POST['ans3'] as $value){
        echo $value.",";
      }
     ?>
  </body>
</html>
