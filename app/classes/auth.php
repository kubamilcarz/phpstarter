<?php
class auth {
     public static $system_cookie_name = "PPS";
     public static $system_login_with_email_or_username = true;
     static function logout() {
          DB::query('DELETE FROM login_tokens WHERE user_id=:userid', array(':userid'=>self::loggedin()));
          setcookie("" . self::$system_cookie_name . "", '1', time()-3600);
          setcookie("" . self::$system_cookie_name . "_", '1', time()-3600);
          header('Location: '.$_SERVER['PHP_SELF']);
     }
     static function isloggedin() {
          if (!self::loggedin()) {
               require("./app/login_again.php");
               exit();
          }
     }

     public static function loggedin() {
          if (isset($_COOKIE['' . self::$system_cookie_name . ''])) {
               if (DB::query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['' . self::$system_cookie_name . ''])))) {
                    $userid = DB::query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['' . self::$system_cookie_name . ''])))[0]['user_id'];
                    if (isset($_COOKIE['' . self::$system_cookie_name . '_'])) {
                         return $userid;
                    } else {
                         $cstrong = True;
                         $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                         DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$userid));
                         DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE["" . self::$system_cookie_name . ""])));
                         setcookie("" . self::$system_cookie_name . "", $token, time() + 60 * 60 * 24 * 30, '/', NULL, NULL, TRUE);
                         setcookie("" . self::$system_cookie_name . "_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
                         return $userid;
                    }
               }
          }
          return false;
     }

     public static function login($username, $password) {
          if (self::$system_login_with_email_or_username == false) {
               if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
                    if (password_verify($password, DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {
                         echo 'Logged in!';
                         $cstrong = True;
                         $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                         $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
                         DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                         setcookie("" . self::$system_cookie_name . "", $token, time() + 60 * 60 * 24 * 30, '/', NULL, NULL, TRUE);
                         setcookie("" . self::$system_cookie_name . "_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
                         header("Location: " . $_SERVER['PHP_SELF'] . "");
                    } else {
                         echo 'Invalid Password!';
                    }
               } else {
                    echo 'Invalid username!';
               }
          }else {
               if (strpos($username, "@") != false) {
                    if (DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$username))) {
                         if (password_verify($password, DB::query('SELECT password FROM users WHERE email=:email', array(':email'=>$username))[0]['password'])) {
                              echo 'Logged in!';
                              $cstrong = True;
                              $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                              $user_id = DB::query('SELECT id FROM users WHERE email=:email', array(':email'=>$username))[0]['id'];
                              DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                              setcookie("" . self::$system_cookie_name . "", $token, time() + 60 * 60 * 24 * 30, '/', NULL, NULL, TRUE);
                              setcookie("" . self::$system_cookie_name . "_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
                              header("Location: " . $_SERVER['PHP_SELF'] . "");
                         } else {
                              echo 'Invalid Password!';
                         }
                    } else {
                         echo 'Invalid username!';
                    }
               }else {
                    if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
                         if (password_verify($password, DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {
                              echo 'Logged in!';
                              $cstrong = True;
                              $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                              $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
                              DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                              setcookie("" . self::$system_cookie_name . "", $token, time() + 60 * 60 * 24 * 30, '/', NULL, NULL, TRUE);
                              setcookie("" . self::$system_cookie_name . "_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
                              header("Location: " . $_SERVER['PHP_SELF'] . "");
                         } else {
                              echo 'Invalid Password!';
                         }
                    } else {
                         echo 'Invalid username!';
                    }
               }
          }
     }

     public static function register($firstname, $lastname, $username, $email, $password, $rpassword) {
          if (!DB::query('SELECT username FROM users WHERE username=:username', [':username'=>$username])) {
          if (strlen($username) >= 4 && strlen($username) <= 32) {
          if (preg_match('/[a-zA-Z0-9_]+/', $username)) {
               if (strlen($password) >= 6 && strlen($password) <= 60) {
               if ($password == $rpassword) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if (!DB::query('SELECT email FROM users WHERE email=:email', [':email'=>$email])) {

                         DB::query('INSERT INTO users VALUES (\'\', :firstname, :lastname, :username, :email, :password)', [':firstname'=>$firstname, ':lastname'=>$lastname, ':username'=>$username, ':email'=>$email, ':password'=>password_hash($password, PASSWORD_BCRYPT)]);
                         Mail::sendMail('Welcome!', 'Your account has been created!', $email);
                         self::login($username, $password);

                    }else {echo 'Email already in use!';}
                    }else {echo 'Invalid email!';}
               }else {echo 'Passwords are not the same!';}
               }else {echo 'Invalid password (min: 4; max: 32)!';}
          }else {echo 'Invalid username!';}
          }else {echo 'Invalid username (min: 4; max: 32)!';}
          }else {echo 'Username already in use!';}
     }
}
