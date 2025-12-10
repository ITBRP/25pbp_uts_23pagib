<?php

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

$koneksi = new mysqli('localhost', 'root', '', 'data_mobil');

if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg" => "Data not found"
    ]);
    exit();
}

$id = (int) $_GET['id'];

$cek = $koneksi->query("SELECT * FROM table_datamobil WHERE id = $id");

if ($cek->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg" => "Data not found"
    ]);
    exit();
}

$q = "DELETE FROM table_datamobil WHERE id = $id";
$delete = $koneksi->query($q);

if (!$delete) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

http_response_code(200);
echo json_encode([
    "status" => "success",
    "msg" => "Delete data success",
    "data" => [
        "id" => $id
    ]
]);
exit();
