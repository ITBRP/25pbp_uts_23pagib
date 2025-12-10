<?php
header("Content-Type: application/json; charset=UTF-8");

// METHOD CHECK
if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "msg" => "METHOD SALAH"
    ]);
    exit();
};

// PARSE PUT DATA
parse_str(file_get_contents("php://input"), $_PUT);

// VALIDASI ID
if (!isset($_GET["id"]) || $_GET["id"] == "") {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "msg" => "ID wajib dikirim"
    ]);
    exit();
}

$id = $_GET["id"];

$errors = [];

// VALIDASI BRAND
if (!isset($_PUT['brand']) || trim($_PUT['brand']) == '') {
    $errors['brand'] = "Brand wajib diisi";
} else {
    if (strlen(trim($_PUT['brand'])) < 2) {
        $errors['brand'] = "Brand minimal 2 karakter";
    }
}

// VALIDASI MODEL
if (!isset($_PUT['model']) || trim($_PUT['model']) == '') {
    $errors['model'] = "Model wajib diisi";
} else {
    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $_PUT['model'])) {
        $errors['model'] = "Model tidak boleh karakter spesial";
    }
}


// VALIDASI YEAR
if (!isset($_PUT['year']) || trim($_PUT['year']) == '') {
    $errors['year'] = "Year wajib diisi";
} else {
    if (!is_numeric($_PUT['year']) ||
        $_PUT['year'] < 1990 ||
        $_PUT['year'] > date("Y")) {
        $errors['year'] = "Format tahun tidak valid";
    }
}

// VALIDASI PRICE
if (!isset($_PUT['price']) || trim($_PUT['price']) == '') {
    $errors['price'] = "Price wajib diisi";
} else {
    if (!is_numeric($_PUT['price']) || $_PUT['price'] <= 0) {
        $errors['price'] = "Price harus angka dan lebih dari 0";
    }
}


// VALIDASI TRANSMISSION
if (!isset($_PUT['transmission']) || !in_array($_PUT['transmission'], ['Manual','Automatic'])) {
    $errors['transmission'] = "Transmission tidak valid";
}

// RETURN DATA ERROR
if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "msg" => "Data error",
        "errors" => $errors
    ]);
    exit();
}

$k = new mysqli("localhost", "root", "", "uts_mobil");

// CEK APAKAH ID TERSEDIA
$cek = $k->query("SELECT * FROM mobil WHERE id='$id'");
if ($cek->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg" => "Data not found"
    ]);
    exit();
}

// SIMPAN DATA BARU
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

if (!$k->query($q)) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

echo json_encode([
    "status" => "success",
    "msg" => "Process success",
    "data" => [
        "id" => $id,
        "brand" => $brand,
        "model" => $model,
        "year" => $year,
        "price" => $price,
        "transmission" => $trans
    ]
]);
