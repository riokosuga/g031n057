<!-- form.php -->

<?php
// パスワードにhogehogeと入力されたらユーザー名を出力する
if ($_POST['password'] !== NULL and $_POST['password'] === 'hogehoge') {
  echo "Hello, {$_POST['username']}! :D";
}
?>

<html>
  <head>
  </head>

  <body>
    <form action="form.php" method="post">
      <input type="text"     name="username" />
      <input type="password" name="password" />
      <input type="submit" />
    </form>
  </body>
</html>
