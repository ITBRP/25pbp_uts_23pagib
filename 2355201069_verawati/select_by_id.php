<?php
header("Content-Type: application/json; charset=UTF-8");

// CEK METHOD HARUS GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Server error'
    ]);
    exit();
}

// CEK PARAMETER ID
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Parameter id wajib dikirim'
    ]);
    exit();
}

$id = $_GET['id'];

// koneksi
$koneksi = new mysqli('localhost', 'root', '', 'db_uts');

if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Koneksi database gagal'
    ]);
    exit();
}

// query select by id
$q = "SELECT id, brand, model, year, price, transmission, photo 
      FROM tabel_vera 
      WHERE id = '$id'
      LIMIT 1";

$result = $koneksi->query($q);

// jika data tidak ditemukan
if ($result->num_rows == 0) {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Data not found'
    ]);
    exit();
}

// ambil data
$row = $result->fetch_assoc();
$row['photo'] = $row['photo'] ? $row['photo'] : "";

echo json_encode([
    'status' => 'success',
    'msg' => 'Process success',
    'data' => $row
]);