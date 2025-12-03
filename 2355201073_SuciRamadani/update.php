<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(["status" => "error", "msg" => "Method salah"]);
    exit();
}

parse_str(file_get_contents("php://input"), $_PUT);

$errors = [];

if (!isset($_GET['id']) || trim($_GET['id']) === '') {
    $errors['id'] = "ID wajib dikirim";
}
$id = $_GET['id'];

if (!isset($_PUT['brand']) || strlen(trim($_PUT['brand'])) < 2) {
    $errors['brand'] = "Brand minimal 2 karakter";
}

if (!isset($_PUT['model']) || !preg_match('/^[a-zA-Z0-9 ]+$/', $_PUT['model'])) {
    $errors['model'] = "Model tidak boleh karakter spesial";
}

if (
    !isset($_PUT['year']) || !is_numeric($_PUT['year'])
    || $_PUT['year'] < 1990 || $_PUT['year'] > date("Y")
) {
    $errors['year'] = "Tahun tidak valid";
}

if (!isset($_PUT['price']) || $_PUT['price'] <= 0) {
    $errors['price'] = "Harga harus lebih dari 0";
}

if (!isset($_PUT['transmission']) || !in_array($_PUT['transmission'], ['Manual', 'Automatic'])) {
    $errors['transmission'] = "Transmission tidak valid";
}

if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "msg" => "Data error", "errors" => $errors]);
    exit();
}

$k = new mysqli("localhost", "root", "", "uts_mobil");

$cek = $k->query("SELECT * FROM mobil WHERE id='$id'");
if ($cek->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["status" => "error", "msg" => "Data not found"]);
    exit();
}

$brand = $_PUT['brand'];
$model = $_PUT['model'];
$year = $_PUT['year'];
$price = $_PUT['price'];
$trans = $_PUT['transmission'];

$q = "UPDATE mobil SET 
        brand='$brand',
        model='$model',
        year='$year',
        price='$price',
        transmission='$trans'
      WHERE id='$id'";

$k->query($q);

echo json_encode([
    "status" => "success",
    "msg" => "Update success",
    "data" => [
        "id" => $id,
        "brand" => $brand,
        "model" => $model,
        "year" => $year,
        "price" => $price,
        "transmission" => $trans
    ]
]);
