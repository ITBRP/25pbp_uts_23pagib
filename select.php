<?php
header("Content-Type: application/json; charset=UTF-8");

// CEK METHOD HARUS GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'status' => 'error',
        'msg' => 'server eror'
    ]);
    exit();
}

// koneksi
$koneksi = new mysqli('localhost', 'root', '', 'db_salsa');

if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Koneksi database gagal'
    ]);
    exit();
}

// query select
$q = "SELECT id, brand, model, year, price, transmission, photo 
      FROM uts 
      ORDER BY id DESC";

$result = $koneksi->query($q);
$data = [];

while ($row = $result->fetch_assoc()) {
    $row['photo'] = $row['photo'] ? $row['photo'] : "";
    $data[] = $row;
}

echo json_encode([
    'status' => 'success',
    'msg' => 'Process success',
    'data' => $data
]);