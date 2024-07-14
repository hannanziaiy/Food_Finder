<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  }
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  }
  exit(0);
}

include_once('dataBase.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  if (isset($data['name'], $data['email'], $data['password'])) {
    $name = $data['name'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);
    $isAdmin = 0;


    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
      echo json_encode(['success' => false, 'message' => 'این ایمیل قبلا استفاده شده است']);
    } else {

      $stmt = $conn->prepare("INSERT INTO users (name, email, isAdmin, pass) VALUES (:name, :email, :isAdmin, :password)");
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':isAdmin', $isAdmin);
      $stmt->bindParam(':password', $password);

      if ($stmt->execute()) {
        echo json_encode(['success' => true]);
      } else {
        echo json_encode(['success' => false, 'message' => 'خطا در ثبت اطلاعات']);
      }
    }
  } else {
    echo json_encode(['success' => false, 'message' => 'ورودی نامعتبر']);
  }
}
