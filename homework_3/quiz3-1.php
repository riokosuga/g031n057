<!-- quiz3-1.php -->

<?php
  echo '興味のある研究分野は何ですか？'
 ?>

 <html>
   <head>
   </head>

   <body>
     <form name="form1" action="quiz3-2.php" method="post">
       <input type="checkbox" name="ans1[]" value="教育" />教育<br />
       <input type="checkbox" name="ans1[]" value="観光" />観光<br />
       <input type="checkbox" name="ans1[]" value="農業" />農業<br />
       <input type="checkbox" name="ans1[]" value="LS" />LS<br />
       <input type="submit"/>
     </form>
   </body>
 </html>
