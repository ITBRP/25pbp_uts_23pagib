<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Method error!'
    ]);
    exit();
}

$koneksi = new mysqli('localhost', 'root', '', 'uts');
if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

$q = "SELECT id, brand, model, year, price, transmission, photo FROM uts ORDER BY id ASC";
$result = $koneksi->query($q);


$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

http_response_code(200);

echo json_encode([
    'status' => 'success',
    'msg' => 'Process success',
    'data' => $data
]);