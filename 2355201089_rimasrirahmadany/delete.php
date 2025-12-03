
<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(["status"=>"error","msg"=>"Method salah"]);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["status"=>"error","msg"=>"ID wajib dikirim"]);
    exit();
}

$id = $_GET['id'];

$k = new mysqli("localhost","root","","db_uts");

$cek = $k->query("SELECT * FROM mobil WHERE id='$id'");
if ($cek->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["status"=>"error","msg"=>"Data not found"]);
    exit();
}

// hapus file foto juga kalau ada
$data = $cek->fetch_assoc();
if ($data['photo'] && file_exists("img/".$data['photo'])) {
    unlink("img/".$data['photo']);
}

$k->query("DELETE FROM mobil WHERE id='$id'");

echo json_encode([
    "status"=>"success",
    "msg"=>"Delete success",
    "data"=>["id"=>$id]
]);