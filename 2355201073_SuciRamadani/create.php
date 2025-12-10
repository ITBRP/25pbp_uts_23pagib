<?php
header("Content-Type: application/json; charset=UTF-8");

// METHOD CHECK
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "msg" => "METHOD SALAH"
    ]);
    exit();
};

$errors = [];

/* VALIDASI */

// BRAND
if (!isset($_POST['brand']) || trim($_POST['brand']) === '') {
    $errors['brand'] = "Brand wajib diisi";
} else if (strlen(trim($_POST['brand'])) < 2) {
    $errors['brand'] = "Brand minimal 2 karakter";
}



// MODEL
if (!isset($_POST['model']) || trim($_POST['model']) == '') {
    $errors['model'] = "Model wajib diisi";
} else {
    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $_POST['model'])) {
        $errors['model'] = "Model tidak boleh karakter spesial";
    }
}


// YEAR
if (!isset($_POST['year']) || trim($_POST['year']) == '') {
    $errors['year'] = "Year wajib diisi";
} else {
    if (
        !is_numeric($_POST['year']) ||
        $_POST['year'] < 1990 ||
        $_POST['year'] > date("Y")
    ) {
        $errors['year'] = "Format tahun tidak valid";
    }
}


// PRICE
if (!isset($_POST['price'])) {
    $errors['price'] = "Price wajib disi";
} else {
    if ($_POST['price'] === '') {
        $errors['price'] = "Price tidak boleh kosong";
    } else if (!is_numeric($_POST['price']) || $_POST['price'] <= 0) {
        $errors['price'] = "Harus berupa angka dan lebih dari 0";
    }
}


// TRANSMISSION
if (isset($_POST['transmission']) && $_POST['transmission'] !== '') {
    if (!in_array($_POST['transmission'], ['Manual', 'Automatic'])) {
        $errors['transmission'] = "Transmission tidak valid";
    }
}

// PHOTO
$anyPhoto = false;
$namaPhoto = "";

if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] !== UPLOAD_ERR_NO_FILE) {

    $allowed = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $errors['photo'] = "Format file tidak valid (hanya jpg, jpeg, png)";
    } else {
        $anyPhoto = true;
        $namaPhoto = md5(time()) . "." . $ext;
    }
}

// VALIDATION ERROR → 400
if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "msg" => "Data error",
        "errors" => $errors
    ]);
    exit();
}

// DATABASE
$k = new mysqli("localhost", "root", "", "db_mobil");

if ($k->connect_errno) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

// Upload file
if ($anyPhoto) {
    if (!is_dir('img')) mkdir('img');
    move_uploaded_file($_FILES["photo"]["tmp_name"], "img/" . $namaPhoto);
}

$brand = $_POST['brand'];
$model = $_POST['model'];
$year  = $_POST['year'];
$price = $_POST['price'];
$trans = $_POST['transmission'] ?? null;

// QUERY
$q = "INSERT INTO mobil (brand, model, year, price, transmission, photo)
      VALUES ('$brand', '$model', '$year', '$price', " .
    ($trans ? "'$trans'" : "NULL") . ", " .
    ($namaPhoto ? "'$namaPhoto'" : "NULL") . ")";

// SERVER ERROR → 500 *FORMAT WAJIB LIKE DOSEN*
if (!$k->query($q)) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

$id = $k->insert_id;

// SUCCESS → 201
http_response_code(201);
echo json_encode([
    "status" => "success",
    "msg" => "Process success",
    "data" => [
        "id" => $id,
        "brand" => $brand,
        "model" => $model,
        "year" => $year,
        "price" => $price,
        "transmission" => $trans,
        "photo" => $namaPhoto
    ]
]);
