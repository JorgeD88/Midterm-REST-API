<?php
header('Content-Type: application/json');

require_once '../config/Database.php';
require_once '../models/Quote.php';

$database = new Database();
$db = $database->connect();
$quote = new Quote($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $author_id = isset($_GET['author_id']) ? (int) $_GET['author_id'] : null;
        $category_id = isset($_GET['category_id']) ? (int) $_GET['category_id'] : null;

        $stmt = $quote->read($id, $author_id, $category_id);

        if ($stmt->rowCount() > 0) {
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } else {
            echo json_encode(['message' => 'No Quotes Found']);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'));

        if (
            !isset($data->quote) ||
            !isset($data->author_id) ||
            !isset($data->category_id) ||
            trim($data->quote) === ''
        ) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        if (!$quote->authorExists($data->author_id)) {
            echo json_encode(['message' => 'author_id Not Found']);
            exit;
        }

        if (!$quote->categoryExists($data->category_id)) {
            echo json_encode(['message' => 'category_id Not Found']);
            exit;
        }

        $quote->quote = trim($data->quote);
        $quote->author_id = (int) $data->author_id;
        $quote->category_id = (int) $data->category_id;

        if ($quote->create()) {
            echo json_encode([
                'id' => (int) $quote->id,
                'quote' => $quote->quote,
                'author_id' => (int) $quote->author_id,
                'category_id' => (int) $quote->category_id
            ]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'));

        if (
            !isset($data->id) ||
            !isset($data->quote) ||
            !isset($data->author_id) ||
            !isset($data->category_id) ||
            trim($data->quote) === ''
        ) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        if (!$quote->exists($data->id)) {
            echo json_encode(['message' => 'No Quotes Found']);
            exit;
        }

        if (!$quote->authorExists($data->author_id)) {
            echo json_encode(['message' => 'author_id Not Found']);
            exit;
        }

        if (!$quote->categoryExists($data->category_id)) {
            echo json_encode(['message' => 'category_id Not Found']);
            exit;
        }

        $quote->id = (int) $data->id;
        $quote->quote = trim($data->quote);
        $quote->author_id = (int) $data->author_id;
        $quote->category_id = (int) $data->category_id;

        if ($quote->update()) {
            echo json_encode([
                'id' => (int) $quote->id,
                'quote' => $quote->quote,
                'author_id' => (int) $quote->author_id,
                'category_id' => (int) $quote->category_id
            ]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'));

        if (!isset($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        if (!$quote->exists($data->id)) {
            echo json_encode(['message' => 'No Quotes Found']);
            exit;
        }

        $quote->id = (int) $data->id;

        if ($quote->delete()) {
            echo json_encode(['id' => (int) $quote->id]);
        }
        break;

    default:
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
