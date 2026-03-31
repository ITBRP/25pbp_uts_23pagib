<?php

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "msg" => "method error"
    ]);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);

$koneksi = new mysqli('localhost', 'root', '', 'data_mobil');

if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg" => "Data not found"
    ]);
    exit();
}

$id = (int) $_GET['id'];

$cek = $koneksi->query("SELECT * FROM table_datamobil WHERE id = $id");

if ($cek->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg" => "Data not found"
    ]);
    exit();
}

$lama = $cek->fetch_assoc();

$errors = [];

if (!isset($input['brand']) || trim($input['brand']) === '') {
    $errors["brand"] = "brand wajib diisi";
}else{
    if(strlen($input['brand'])<2){
       $errors["brand"] = "minimial 2 karakter"; 
    }
}

if (!isset($input['model']) || trim($input['model']) === '') {
    $errors["model"] = "model wajib diisi";
}else{
    if(!preg_match('/^[A-Za-z0-9 ]+$/', $input['model'])){
       $errors["brand"] = "tidak boleh karakter spesial"; 
    }
}

$yearNow = date("Y");


if (!isset($input['year']) || trim($input['year']) === '') {
    $errors['year'] = "year harus diisi";
} else {
    $year = $input['year'];

    if (!preg_match('/^[0-9]{4}$/', $year)) {
        $errors['year'] = "Format tahun tidak valid";
    } elseif ($year < 1990 || $year > $yearNow) {
        $errors['year'] = "Format tahun tidak valid";
    }
}

if (!isset($input['price']) || trim($input['price']) == '') {
    $errors['price'] = "price wajib diisi";
} else {
    if (!is_numeric($input['price']) || $input['price'] <= 0) {
        $errors['price'] = "Harus berupa angka dan lebih dari 0";
    }
}

if (isset($input['transmission']) && $input['transmission'] !== "") {
    $valid = ["Manual", "Automatic"];
    if (!in_array($input['transmission'], $valid)) {
        $errors["transmission"] = "Nilai tidak valid (hanya Manual atau Automatic)";
    }
}

if (isset($input['photo']) && trim($input['photo']) !== "") {
    $namaPhoto = $input['photo'];
} else {
    $namaPhoto = $lama['photo'];
}

if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "msg" => "Data error",
        "errors" => $errors
    ]);
    exit();
}

$brand = $input['brand'];
$model = $input['model'];
$year = $input['year'];
$price = $input['price'];
$transmission = $input['transmission'] ?? $lama['transmission'];

$q = "UPDATE table_datamobil SET
        brand = '$brand',
        model = '$model',
        year = '$year',
        price = '$price',
        transmission = " . ($transmission ? "'$transmission'" : "NULL") . ",
        photo = " . ($namaPhoto ? "'$namaPhoto'" : "NULL") . "
      WHERE id = $id";

if (!$koneksi->query($q)) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

http_response_code(200);
echo json_encode([
    "status" => "success",
    "msg" => "Process success",
    "data" => [
        "id" => $id,
        "brand" => $brand,
        "model" => $model,
        "year" => (int)$year,
        "price" => (int)$price,
        "transmission" => $transmission,
        "photo" => $namaPhoto
    ]
]);
exit();
