<?php

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'msg' => 'server error !'
    ]);
    exit();
}

// block jika method benar
$errors = [];

/* ================= BRAND ================= */
if (!isset($_POST['brand']) || trim($_POST['brand']) === '') {
    $errors['brand'] = "Minimal 2 karakter";
} else {
    if (strlen(trim($_POST['brand'])) < 2) {
        $errors['brand'] = "Minimal 2 karakter";
    }
}

/* ================= MODEL ================= */
if (!isset($_POST['model']) || trim($_POST['model']) === '') {
    $errors['model'] = "Tidak boleh karakter spesial";
} else {
    if (!preg_match('/^[A-Za-z0-9 ]+$/', $_POST['model'])) {
        $errors['model'] = "Tidak boleh karakter spesial";
    }
}

/* ================= YEAR ================= */
$yearNow = date('Y');

if (!isset($_POST['year']) || trim($_POST['year']) === '') {
    $errors['year'] = "Format tahun tidak valid";
} else {
    $year = $_POST['year'];

    if (!preg_match('/^[0-9]{4}$/', $year)) {
        $errors['year'] = "Format tahun tidak valid";
    } elseif ($year < 1990 || $year > $yearNow) {
        $errors['year'] = "Format tahun tidak valid";
    }
}

/* ================= PRICE ================= */
if (!isset($_POST['price']) || trim($_POST['price']) === '') {
    $errors['price'] = "Harus berupa angka dan lebih dari 0";
} else {
    if (!is_numeric($_POST['price']) || $_POST['price'] <= 0) {
        $errors['price'] = "Harus berupa angka dan lebih dari 0";
    }
}

/* ================= TRANSMISSION =================*/
if (isset($_POST['transmission']) && trim($_POST['transmission']) !== '') {
    $validTrans = ['Manual', 'Automatic'];
    if (!in_array($_POST['transmission'], $validTrans)) {
        $errors['transmission'] = "Nilai tidak valid (hanya Manual atau Automatic)";
    }
}

/* ================= PHOTO ================= */
$anyPhoto = false;
$namaPhoto = null;

if (isset($_FILES['photo'])) {
    if ($_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {

        $allowed = ['jpg', 'jpeg', 'png'];
        $fileName = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $errors['photo'] = "Format file tidak valid (hanya jpg, jpeg, png)";
        } else {
            $anyPhoto = true;
            $namaPhoto = md5(date('dmyhis')) . "." . $ext;
        }
    }
}

/* ================= ERROR RESPONSE ================= */
if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'msg' => 'data error!',
        'errors' => $errors
    ]);
    exit();
}

/* ================= INSERT DB ================= */
$koneksi = new mysqli('localhost', 'root', '', 'data_mobil');

$brand = $_POST['brand'];
$model = $_POST['model'];
$year  = $_POST['year'];
$price = $_POST['price'];
$transmission = $_POST['transmission'] ?? null;

if ($anyPhoto) {
    move_uploaded_file($_FILES['photo']['tmp_name'], 'img/' . $namaPhoto);
}

$q = "INSERT INTO table_datamobil(brand, model, year, price, transmission, photo) 
      VALUES('$brand', '$model', '$year', '$price', " . 
      ($transmission ? "'$transmission'" : "NULL") . ", " . 
      ($namaPhoto ? "'$namaPhoto'" : "NULL") . ")";

$koneksi->query($q);

/* ================= SUCCESS RESPONSE ================= */
http_response_code(201);

echo json_encode([
    'status' => "success",
    'msg' => "proses sukses",
    'data' => [
        'brand' => $brand,
        'model' => $model,
        'year' => $year,
        'price' => $price,
        'transmission' => $transmission,
        'photo' => $namaPhoto
    ]
]);
exit();
