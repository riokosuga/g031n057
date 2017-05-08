<!-- quiz3-2.php -->

<?php
  echo 'あなたの血液型は何ですか？'
 ?>

 <html>
   <head>
   </head>

   <body>
     <form name="form1" action="quiz3-3.php" method="post">
       <input type="checkbox" name="ans2[]" value="A型" />A型<br />
       <input type="checkbox" name="ans2[]" value="B型" />B型<br />
       <input type="checkbox" name="ans2[]" value="O型" />O型<br />
       <input type="checkbox" name="ans2[]" value="AB型" />AB型<br />

       <?php
       foreach ($_POST['ans1'] as $value){
         echo '<input type="hidden" name="ans1[]" value="' . $value .'">';
       }
        ?>
       <input type="submit"/>
     </form>
   </body>
 </html>
