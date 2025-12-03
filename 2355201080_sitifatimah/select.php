<?php
header("Content-Type: application/json; charset=UTF-8");

// METHOD CHECK (sesuai permintaan kamu → 500)
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server Error"
    ]);
    exit();
}

$k = new mysqli("localhost", "root", "", "db_mobil");

$q = "SELECT * FROM mobil ORDER BY id DESC";
$res = $k->query($q);

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "msg" => "Process success",
    "data" => $data
]);