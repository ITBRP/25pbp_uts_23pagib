
<?php
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_POST['id'])) {
    http_response_code(400);
    echo json_encode(["status"=>"error","msg"=>"ID wajib dikirim"]);
    exit();
}

$id = $_GET['id'];

$k = new mysqli("localhost","root","","db_uts");

$q = "SELECT * FROM mobil WHERE id='$id'";
$res = $k->query($q);

if ($res->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["status"=>"error","msg"=>"Data not found"]);
    exit();
}

echo json_encode([
    "status"=>"success",
    "msg"=>"Process success",
    "data"=>$res->fetch_assoc()
]);