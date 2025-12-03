<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status"=>"error","msg"=>"Gunakan POST untuk update"]);
    exit();
}

if (!isset($_POST['id'])) {
    echo json_encode(["status"=>"error","msg"=>"ID wajib"]);
    exit();
}

$id = $_POST['id'];

$k = new mysqli("localhost","root","","uts1_mobil");
$cek = $k->query("SELECT photo FROM mobil WHERE id='$id'");

if ($cek->num_rows === 0) {
    echo json_encode(["status"=>"error","msg"=>"Data tidak ditemukan"]);
    exit();
}

$old = $cek->fetch_assoc();

$brand = $_POST['brand'] ?? $old['brand'];
$model = $_POST['model'] ?? $old['model'];
$year = $_POST['year'] ?? $old['year'];
$price = $_POST['price'] ?? $old['price'];
$trans = $_POST['transmission'] ?? $old['transmission'];

$newPhoto = $old['photo'];

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $newPhoto = md5(time()).".".$ext;

    if ($old['photo'] && file_exists("img/".$old['photo'])) unlink("img/".$old['photo']);
    move_uploaded_file($_FILES['photo']['tmp_name'], "img/".$newPhoto);
}

$q = "UPDATE mobil SET brand='$brand', model='$model', year='$year', price='$price', transmission='$trans', photo='$newPhoto' WHERE id='$id'";
$k->query($q);

echo json_encode([
    "status"=>"success",
    "msg"=>"Update berhasil",
    "updated_id"=>$id
]);
