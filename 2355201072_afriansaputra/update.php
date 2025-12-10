<?php

header("Content-Type: application/json; charset=UTF-8");
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "msg"    => "Methode error"
    ]);
    exit();
}

$rawBody = file_get_contents("php://input");
$data    = json_decode($rawBody, true);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg"    => "Data not found"
    ]);
    exit();
}

$id = (int) $_GET['id'];
$koneksi = new mysqli('localhost', 'root', '', 'uts_4pagib');

if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => "Server error"
    ]);
    exit();
}

$cek = $koneksi->query("SELECT * FROM mobil WHERE id = $id");

if (!$cek) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => "Server error"
    ]);
    exit();
}

if ($cek->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg"    => "Data not found"
    ]);
    exit();
}

$lama   = $cek->fetch_assoc();
$errors = [];

if (!isset($data['brand']) || trim($data['brand']) === '' || strlen(trim($data['brand'])) < 2) {
    $errors['brand'] = "Minimal 2 karakter";
}

if (
    !isset($data['model']) || trim($data['model']) === '' ||
    !preg_match('/^[A-Za-z0-9 ]+$/', $data['model'])
) {
    $errors['model'] = "Tidak boleh karakter spesial";
}

$tahunSekarang = date("Y");
if (
    !isset($data['year']) ||
    !preg_match('/^[0-9]{4}$/', $data['year']) ||
    $data['year'] < 1990 ||
    $data['year'] > $tahunSekarang
) {
    $errors['year'] = "Format tahun tidak valid";
}

if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
    $errors['price'] = "Harus berupa angka dan lebih dari 0";
}


if (isset($data['photo']) && trim($data['photo']) !== "") {

    $namaPhoto = trim($data['photo']);
    $ext = strtolower(pathinfo($namaPhoto, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];

    if ($ext === "" || !in_array($ext, $allowed)) {
        $errors['photo'] = "Format file tidak valid (hanya jpg, jpeg, png)";
    }

} else {
    $namaPhoto = $lama['photo'];
}


if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "msg"    => "Data error",
        "errors" => $errors
    ]);
    exit();
}

$brand  = $data['brand'];
$model  = $data['model'];
$year   = $data['year'];
$price  = $data['price'];
$transmission = isset($data['transmission']) && $data['transmission'] !== ''
    ? $data['transmission']
    : $lama['transmission'];

$q = "UPDATE mobil SET
        brand        = '$brand',
        model        = '$model',
        year         = '$year',
        price        = '$price',
        transmission = " . ($transmission ? "'$transmission'" : "NULL") . ",
        photo        = " . ($namaPhoto ? "'$namaPhoto'" : "NULL") . "
      WHERE id = $id";

$update = $koneksi->query($q);

if (!$update) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => "Server error"
    ]);
    exit();
}

http_response_code(200);
echo json_encode([
    "status" => "success",
    "msg"    => "Process success",
    "data"   => [
        "id"           => $id,
        "brand"        => $brand,
        "model"        => $model,
        "year"         => (int)$year,
        "price"        => (int)$price,
        "transmission" => $transmission,
        "photo"        => $namaPhoto
    ]
]);
exit();
