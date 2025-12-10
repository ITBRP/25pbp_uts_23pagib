<?php

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "msg"    => "Methode error!"
    ]);
    exit();
}

$db = new mysqli('localhost', 'root', '', 'uts_4pagib');
$sql = "SELECT * FROM mobil";
$run = $db->query($sql);


$data = [];
while ($r = $run->fetch_assoc()) {
    $data[] = [
        "id"           => (int)$r['id'],
        "brand"        => $r['brand'],
        "model"        => $r['model'],
        "year"         => (int)$r['year'],
        "price"        => (int)$r['price'],
        "transmission" => $r['transmission'],
        "photo"        => $r['photo']
    ];
}


http_response_code(200);
echo json_encode([
    "status" => "success",
    "msg"    => "Process success",
    "data"   => $data
]);
exit();

