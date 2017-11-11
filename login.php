<?php require_once("./app/init.php");
if (isset($_POST['loginbtn'])) {
     auth::login($_POST['username'], $_POST['password']);
}
if (auth::loggedin()) {
     header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="<?php echo $system_lang; ?>">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title><?php echo $system_name; ?></title>
     <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>
     <form action="" method="post">
          <?php if (auth::$system_login_with_email_or_username == true) {
               echo '<input type="email" name="username" placeholder="email or username">';
          }else {
               echo '<input type="text" name="username" placeholder="username">';
          } ?>
          <input type="password" placeholder="password" name="password">
          <button type="submit" name="loginbtn"><i class="fa fa-sign-in"></i> zaloguj się</button>
     </form>
     <script src="assets/js/jquery.js"></script>
     <script src="assets/js/functions.js"></script>
</body>
</html>
