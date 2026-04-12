<?php
require_once '../cors.php';
header('Content-Type: application/json');

require_once '../config/Database.php';
require_once '../models/Category.php';

$database = new Database();
$db = $database->connect();
$category = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $stmt = $category->read($id);

        if ($stmt->rowCount() > 0) {
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } else {
            echo json_encode(['message' => 'category_id Not Found']);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->category) || trim($data->category) === '') {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        $category->category = trim($data->category);

        if ($category->create()) {
            echo json_encode([
                'id' => (int) $category->id,
                'category' => $category->category
            ]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->id) || !isset($data->category) || trim($data->category) === '') {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        if (!$category->exists($data->id)) {
            echo json_encode(['message' => 'category_id Not Found']);
            exit;
        }

        $category->id = (int) $data->id;
        $category->category = trim($data->category);

        if ($category->update()) {
            echo json_encode([
                'id' => (int) $category->id,
                'category' => $category->category
            ]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        if (!$category->exists($data->id)) {
            echo json_encode(['message' => 'category_id Not Found']);
            exit;
        }

        $category->id = (int) $data->id;

        if ($category->delete()) {
            echo json_encode(['id' => (int) $category->id]);
        }
        break;

    default:
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
