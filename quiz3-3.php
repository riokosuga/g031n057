<!-- quiz3-3.php -->

<?php
  echo 'あなたの好きな言語は何ですか？'
 ?>

 <html>
   <head>
   </head>

   <body>
     <form name="form1" action="quiz_result.php" method="post">
       <input type="checkbox" name="ans3[]" value="PHP" />PHP<br />
       <input type="checkbox" name="ans3[]" value="Java" />Java<br />
       <input type="checkbox" name="ans3[]" value="C#" />C#<br />
       <input type="checkbox" name="ans3[]" value="Ruby" />Ruby<br />

       <?php
       foreach ($_POST['ans1'] as $value){
         echo '<input type="hidden" name="ans1[]" value="' . $value .'">';
       }

       foreach ($_POST['ans2'] as $value){
         echo '<input type="hidden" name="ans2[]" value="' . $value .'">';
       }
        ?>
       <input type="submit"/>
     </form>
   </body>
 </html>
