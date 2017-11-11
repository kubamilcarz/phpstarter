<?php
class DB {
     private static $system_dbname = "phpstarter";
     private static $system_dbpassword = "";
     private static $system_dbusername = "root";

     private static function connect() {
          $pdo = new PDO('mysql:host=127.0.0.1;dbname=' . self::$system_dbname . ';charset=utf8', '' . self::$system_dbusername . '', '' . self::$system_dbpassword . '');
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $pdo;
     }
     public static function query($query, $params = array()) {
          $statement = self::connect()->prepare($query);
          $statement->execute($params);
          if (explode(' ', $query)[0] == 'SELECT') {
               $data = $statement->fetchAll();
               return $data;
          }
     }
}
