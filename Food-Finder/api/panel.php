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

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
  echo json_encode(['error' => 'Invalid JSON']);
  exit;
}

$action = $data['action'];
$title = $data['title'] ?? null;
$mavad = $data['ingredients'] ?? null;
$instruction = $data['method'] ?? null;
$cat = $data['category'] ?? null;
$sub_cat = $data['subCategory'] ?? null;
$prepare_time = $data['prepareTime'] ?? null;
$cook_time = $data['cookTime'] ?? null;
$nafar = $data['nafar']  ?? null;

try {
  if ($action === 'submit') {
    $sql = "INSERT INTO cook (title, mavad, instruction, cat, sub_cat, prepare_time, cook_time, nafar) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$title, $mavad, $instruction, $cat, $sub_cat, $prepare_time, $cook_time, $nafar]);
    echo json_encode(['message' => 'Recipe added successfully']);
  } elseif ($action === 'edit') {
    $id = $data['id'] ?? null;
    if ($id) {
      $sql = "UPDATE cook SET title = ?, mavad = ?, instruction = ?, cat = ?, sub_cat = ?, prepare_time = ?, cook_time = ?, nafar = ? WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$title, $mavad, $instruction, $cat, $sub_cat, $prepare_time, $cook_time, $nafar, $id]);
      echo json_encode(['message' => 'Recipe updated successfully']);
    } else {
      echo json_encode(['error' => 'Invalid ID for editing']);
    }
  } elseif ($action === 'delete') {
    $id = $data['id'] ?? null;
    if ($id) {
      $sql = "DELETE FROM cook WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$id]);
      echo json_encode(['message' => 'Recipe deleted successfully']);
    } else {
      echo json_encode(['error' => 'Invalid ID for deletion']);
    }
  } else {
    echo json_encode(['error' => 'Invalid action']);
  }
} catch (PDOException $e) {
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
