<?php
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["status"=>"error","msg"=>"ID diperlukan"]);
    exit();
}

$id = $_GET['id'];

$k = new mysqli("localhost","root","","uts1_mobil");
$q = "SELECT * FROM mobil WHERE id='$id'";
$res = $k->query($q);

if ($res->num_rows === 0) {
    echo json_encode(["status"=>"error","msg"=>"Data tidak ditemukan"]);
    exit();
}

echo json_encode([
    "status"=>"success",
    "msg"=>"Process success",
    "data"=>$res->fetch_assoc()
]);
