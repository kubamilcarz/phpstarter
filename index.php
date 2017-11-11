<?php require_once("./app/init.php");?>
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
          <button type="submit" name="signoutbtn"><i class="fa fa-sign-out"></i> Wyloguj się</button>
     </form>

     <script src="assets/js/jquery.js"></script>
     <script src="assets/js/functions.js"></script>
</body>
</html>
