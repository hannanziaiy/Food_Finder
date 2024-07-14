<?php

$host =  "localhost";
$username =  "root";
$password =  '';
$db = 'ashpazi';


try {
  $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
} catch (PDOException $e) {
  echo "خطا در اتصال به پایگاه داده: " . $e->getMessage();
  die();
}
