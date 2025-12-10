<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'msg' => 'server error'
    ]);
    exit();
}

$koneksi = new mysqli('localhost', 'root', '', 'db_uts');
if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Database connection error'
    ]);
    exit();
}

// Ambil ID dari parameter URL
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Data error',
        'errors' => [
            'id' => 'ID wajib dikirim'
        ]
    ]);
    exit();
}

$id = intval($_GET['id']);

$q = "DELETE FROM data_mobil WHERE id = $id";
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