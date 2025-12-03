<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["status"=>"error","msg"=>"Gunakan DELETE"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["status"=>"error","msg"=>"ID wajib"]);
    exit();
}

$id = $data['id'];

$k = new mysqli("localhost","root","","uts1_mobil");
$cek = $k->query("SELECT photo FROM mobil WHERE id='$id'");

if ($cek->num_rows === 0) {
    echo json_encode(["status"=>"error","msg"=>"Data tidak ditemukan"]);
    exit();
}

$row = $cek->fetch_assoc();
if ($row['photo'] && file_exists("img/".$row['photo'])) unlink("img/".$row['photo']);

$k->query("DELETE FROM mobil WHERE id='$id'");

echo json_encode([
    "status"=>"success",
    "msg"=>"Data berhasil dihapus",
    "deleted_id"=>$id
]);
