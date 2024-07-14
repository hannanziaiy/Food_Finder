<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}


include_once('dataBase.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $data = json_decode(file_get_contents('php://input'), true);
  if (isset($data['email'], $data['password'])) {
    $email = $data['email'];
    $password = $data['password'];


    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

      if (password_verify($password, $user['pass'])) {
        session_start();

        $_SESSION['data'] = $user;

        $response = array(
          'success' => true,
          'message' => 'ورود موفقیت‌آمیز',
          'user' => $user,
          'session_id' => session_id()
        );
      } else {

        $response = array(
          'success' => false,
          'message' => 'رمز عبور اشتباه است',
        );
      }
    } else {

      $response = array(
        'success' => false,
        'message' => 'کاربری با این ایمیل یافت نشد',
      );
    }
  } else {

    $response = array(
      'success' => false,
      'message' => 'ورودی نامعتبر',
    );
  }

  header('Content-Type: application/json');
  echo json_encode($response);
}
