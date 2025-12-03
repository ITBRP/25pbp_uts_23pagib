<?php

header("Content-Type: application/json; charset=UTF-8");
if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => "Server error"
    ]);
    exit();
}
$id = isset($_GET['id']) ? $_GET['id'] : "";

if ($id === "" || !is_numeric($id)) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg"    => "Data not found"
    ]);
    exit();
}

$db = new mysqli('localhost', 'root', '', 'uts_4pagib');

if ($db->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => "Server error"
    ]);
    exit();
}


$sql = "SELECT * FROM mobil WHERE id = ".$id;
$run = $db->query($sql);

if (!$run) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => "Server error"
    ]);
    exit();
}

if ($run->num_rows < 1) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg"    => "Data not found"
    ]);
    exit();
}


$r = $run->fetch_assoc();

$data = [
    "id"           => (int)$r['id'],
    "brand"        => $r['brand'],
    "model"        => $r['model'],
    "year"         => (int)$r['year'],
    "price"        => (int)$r['price'],
    "transmission" => $r['transmission'],
    "photo"        => $r['photo']
];

http_response_code(200);
echo json_encode([
    "status" => "success",
    "msg"    => "Process success",
    "data"   => $data
]);
exit();
