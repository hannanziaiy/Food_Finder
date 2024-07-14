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

$tableCook = 'cook';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (isset($_GET['requestType'])) {
    $requestType = $_GET['requestType'];

    switch ($requestType) {
      case 'allRecipes':
        $data = getAllRecipes($conn, $tableCook);
        break;
      case 'selectedIngredients':
        $data = getRecipesByIngredients($conn, $tableCook);
        break;
      case 'ingredients':
        $data = getIngredients($conn, $tableCook);
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

function getAllRecipes($conn, $tableCook)
{
  $stmt = $conn->prepare("SELECT * FROM {$tableCook}");
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getRecipesByIngredients($conn, $tableCook)
{
  if (isset($_GET['ingredients']) && is_array($_GET['ingredients'])) {
    $ingredients = implode(',', $_GET['ingredients']);
    $stmt = $conn->prepare("SELECT * FROM {$tableCook} WHERE ingredient IN ({$ingredients})");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } else {
    return [];
  }
}

function getIngredients($conn, $tableCook)
{
  $stmt = $conn->prepare("SELECT DISTINCT mavad FROM {$tableCook}");
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
