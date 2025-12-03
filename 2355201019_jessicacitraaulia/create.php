<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status"=>"error","msg"=>"Method salah"]);
    exit();
}

$errors = [];

// Validasi brand
if (!isset($_POST['brand']) || strlen(trim($_POST['brand'])) < 2) {
    $errors['brand'] = "Brand minimal 2 karakter";
}

// Validasi model
if (!isset($_POST['model']) || !preg_match('/^[a-zA-Z0-9 ]+$/', $_POST['model'])) {
    $errors['model'] = "Model tidak boleh karakter spesial";
}

// Validasi year
if (!isset($_POST['year']) || !is_numeric($_POST['year']) ||
    $_POST['year'] < 1990 || $_POST['year'] > date("Y")) {
    $errors['year'] = "Tahun tidak valid";
}

// Validasi price
if (!isset($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
    $errors['price'] = "Harga harus lebih dari 0";
}

// Validasi transmission
if (!isset($_POST['transmission']) || !in_array($_POST['transmission'], ['Manual','Automatic'])) {
    $errors['transmission'] = "Transmission tidak valid";
}

// Validasi foto
$anyPhoto = false;
$namaPhoto = null;

if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
    $allowed = ['jpg','jpeg','png'];
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $errors['photo'] = "Foto harus jpg, jpeg, atau png";
    } else {
        $anyPhoto = true;
        $namaPhoto = md5(time()) . "." . $ext;
    }
}

if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode(["status"=>"error","msg"=>"Data error","errors"=>$errors]);
    exit();
}

$k = new mysqli("localhost", "root", "", "uts1_mobil");

// Generate ID VARCHAR
$id = "MBL" . time() . rand(100,999);

// Simpan foto jika ada
if ($anyPhoto) {
    if (!is_dir("img")) mkdir("img", 0777, true);
    move_uploaded_file($_FILES['photo']['tmp_name'], "img/" . $namaPhoto);
}

$brand = $_POST['brand'];
$model = $_POST['model'];
$year = $_POST['year'];
$price = $_POST['price'];
$trans = $_POST['transmission'];

$q = "INSERT INTO mobil (id, brand, model, year, price, transmission, photo)
      VALUES ('$id', '$brand', '$model', '$year', '$price', '$trans', '$namaPhoto')";

$k->query($q);

http_response_code(201);
echo json_encode([
    "status"=>"success",
    "msg"=>"Insert success",
    "data"=>[
        "id"=>$id,
        "brand"=>$brand,
        "model"=>$model,
        "year"=>$year,
        "price"=>$price,
        "transmission"=>$trans,
        "photo"=>$namaPhoto
    ]
]);
