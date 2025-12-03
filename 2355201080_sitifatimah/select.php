<?php
header("Content-Type: application/json; charset=UTF-8");

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
