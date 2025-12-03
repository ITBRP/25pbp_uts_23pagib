<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Server error!'
    ]);
    exit();
}

$koneksi = new mysqli('localhost', 'root', '', 'mobil');
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


$q = "SELECT id, brand, model, year, price, transmission, photo FROM car WHERE id = $id 
      LIMIT 1";

$result = $koneksi->query($q);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();

    echo json_encode([
        'status' => 'success',
        'msg' => 'Process success',
        'data' => $data
    ]);
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Data not found'
    ]);
}
