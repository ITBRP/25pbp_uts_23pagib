<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Method error'
    ]);
    exit();
}

$koneksi = new mysqli('localhost', 'root', '', 'db_salsa');
if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}
$id = intval($_GET['id']);

$q = "DELETE FROM uts WHERE id = $id";
$result = $koneksi->query($q);

if ($koneksi->affected_rows > 0) {
    echo json_encode([
        'status' => 'success',
        'msg' => 'Delete data success',
        'data' => [
            'id' => $id
        ]
    ]);
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Data not found'
    ]);
}