<?php
require_once('./app/init.php');
DB::query('DELETE FROM login_tokens WHERE user_id=:userid', array(':userid'=>auth::loggedin()));
setcookie('' . auth::$system_cookie_name . '', '1', time()-3600);
setcookie('' . auth::$system_cookie_name . '_', '1', time()-3600);
header('Location: index.php');
?>
