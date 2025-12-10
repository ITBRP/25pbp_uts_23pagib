<?php

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error!"
    ]);
    exit();
}

$koneksi = new mysqli('localhost', 'root', '', 'data_mobil');

if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error!"
    ]);
    exit();
}

$q = "SELECT * FROM table_datamobil";
$result = $koneksi->query($q);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error!"
    ]);
    exit();
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => (int)$row['id'],
        "brand" => $row['brand'],
        "model" => $row['model'],
        "year" => (int)$row['year'],
        "price" => (int)$row['price'],
        "transmission" => $row['transmission'],
        "photo" => $row['photo']
    ];
}

http_response_code(200);
echo json_encode([
    "status" => "success",
    "msg" => "Process success",
    "data" => $data
]);
exit();
?>
