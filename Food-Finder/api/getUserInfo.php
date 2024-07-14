<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}
include_once('dataBase.php');


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['requestType'])) {
    $requestType = $_GET['requestType'];

    switch ($requestType) {
      case 'allUsers':
        if (isset($_SESSION['data'])) {
          $data = $_SESSION['data'];
        } else {
          $data =  false;
        }
        break;
      case 'loginUser':

        $data = ['loggedIn' => false];

        break;
      default:
        $data = [];
        break;
    }
  } else {
    $data = [];
  }

  header('Content-Type: application/json');
  echo json_encode($data);
}
