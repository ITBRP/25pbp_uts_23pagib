<?php

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => "Server error"
    ]);
    exit();
}

$idParam = isset($_GET['id']) ? trim($_GET['id']) : '';

if ($idParam === '' || !is_numeric($idParam)) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg"    => "Data not found"
    ]);
    exit();
}

$id = (int)$idParam;
$db = new mysqli('localhost', 'root', '', 'uts_4pagib');

if ($db->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => "Server error"
    ]);
    exit();
}

$check = $db->query("SELECT id FROM mobil WHERE id = $id");

if (!$check) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => "Server error"
    ]);
    exit();
}

if ($check->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg"    => "Data not found"
    ]);
    exit();
}

$sqlDelete = "DELETE FROM mobil WHERE id = $id";
$runDelete = $db->query($sqlDelete);

if (!$runDelete) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => "Server error"
    ]);
    exit();
}

http_response_code(200);
echo json_encode([
    "status" => "success",
    "msg"    => "Delete data success",
    "data"   => [
        "id" => $id
    ]
]);
exit();
