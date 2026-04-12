<?php
require_once '../cors.php';
header('Content-Type: application/json');

require_once '../config/Database.php';
require_once '../models/Author.php';

$database = new Database();
$db = $database->connect();
$author = new Author($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $stmt = $author->read($id);

        if ($stmt->rowCount() > 0) {
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } else {
            echo json_encode(['message' => 'author_id Not Found']);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->author) || trim($data->author) === '') {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        $author->author = trim($data->author);

        if ($author->create()) {
            echo json_encode([
                'id' => (int) $author->id,
                'author' => $author->author
            ]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->id) || !isset($data->author) || trim($data->author) === '') {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        if (!$author->exists($data->id)) {
            echo json_encode(['message' => 'author_id Not Found']);
            exit;
        }

        $author->id = (int) $data->id;
        $author->author = trim($data->author);

        if ($author->update()) {
            echo json_encode([
                'id' => (int) $author->id,
                'author' => $author->author
            ]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        if (!$author->exists($data->id)) {
            echo json_encode(['message' => 'author_id Not Found']);
            exit;
        }

        $author->id = (int) $data->id;

        if ($author->delete()) {
            echo json_encode(['id' => (int) $author->id]);
        }
        break;

    default:
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
