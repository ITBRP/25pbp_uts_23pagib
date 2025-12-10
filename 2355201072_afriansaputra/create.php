<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    $res = [
        'status' => 'error',
        'msg' => 'Method salah !'
    ];
    echo json_encode($res);
    exit();
}

$errors = [];
if (!isset($_POST['brand'])) {
    $errors['brand'] = "brand wajib dikirim";
} else {
    if ($_POST['brand'] == '') {
        $errors['brand'] = "brand tidak boleh kosong";
    } else {
        if (strlen($_POST['brand']) < 2) {
            $errors['brand'] = "Format brand salah, Minimal 2 karakter";
        }
    }
}

if (!isset($_POST['model'])) {
    $errors['model'] = "model wajib dikirim";
} else {
    if (!isset($_POST['model']) || trim($_POST['model']) === '') {
        $errors['model'] = "Tidak boleh kosong";
    } else {
        if (!preg_match('/^[A-Za-z0-9 ]+$/', $_POST['model'])) {
            $errors['model'] = "Tidak boleh karakter spesial";
        }
    }
}

if (!isset($_POST['year'])) {
    $errors['year'] = "Year wajib dikirim!";
} else {
    $year = trim($_POST['year']);
    if ($year === "") {
        $errors['year'] = "Year tidak boleh kosong";
    } elseif (!ctype_digit($year) || strlen($year) != 4) {
        $errors['year'] = "Format tahun tidak valid";
    } else {
        $yearInt = (int) $year;
        $currentYear = (int) date("Y");
        if ($yearInt < 1990 || $yearInt > $currentYear) {
            $errors['year'] = "Format tahun tidak valid";
        }
    }
}

if (!isset($_POST['price'])) {
    $errors['price'] = "Price wajib dikirim";
} else {
    $price = trim($_POST['price']);

    if ($price === '') {
        $errors['price'] = "Price tidak boleh kosong";
    } else {
        if (!preg_match('/^[0-9]+$/', $price)) {
            $errors['price'] = "Price harus berisi angka saja";
        } else {
            if ((int)$price <= 0) {
                $errors['price'] = "Harus berupa angka dan lebih dari 0";
            }
        }
    }
}

if (!isset($_POST['transmission'])) {
    $errors['transmission'] = "Transmission wajib dikirim";
} else {
    $transmission = trim($_POST['transmission']);

    if ($transmission === '') {
        $errors['transmission'] = "Transmission tidak boleh kosong";
    } else {
        $allowed = ['Manual', 'Automatic'];

        if (!in_array($transmission, $allowed)) {
            $errors['transmission'] = "Transmission harus Manual atau Automatic";
        }
    }
}


$anyPhoto = false;
$namaPhoto = 'null';

if (isset($_FILES['photo'])) {

    if ($_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $fileName = $_FILES['photo']['name']; //namaaslifile.JPEG, docx
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // hasilnya jadi jpeg

        if (!in_array($fileExt, $allowed)) {
            $errors['photo'] = "Format file tidak valid (hanya jpg, jpeg, png)";
        } else {
            $anyPhoto = true; // photo valid, siap disave
            $namaPhoto = md5(date('dmyhis')) . "." . $fileExt; // fjsadlfjiajflsdjflsadkjfsad.jpeg
        }
    }
}

if (count($errors) > 0) {
    http_response_code(400);
    $res = [
        'status' => 'error',
        'msg' => 'Server error',
        'errors' => $errors
    ];
    echo json_encode($res);
    exit();
}

$koneksi = new mysqli('localhost', 'root', '', 'uts_4pagib');
$brand        = $_POST['brand'];
$model        = $_POST['model'];
$year         = $_POST['year'];
$price        = $_POST['price'];
$transmission = $_POST['transmission'] ?? null;
if ($anyPhoto) {
    move_uploaded_file($_FILES['photo']['tmp_name'], 'img/' . $namaPhoto);
}

$q = "INSERT INTO mobil (brand, model, year, price, transmission, photo)
      VALUES ('$brand', '$model', '$year', '$price', '$transmission', '$namaPhoto')";

$koneksi->query($q);

$id = $koneksi->insert_id;

http_response_code(201);
echo json_encode([
    'status' => 'ok',
    'msg'    => 'Proses berhasil',
    'data'   => [
        'id'           => $id,
        'brand'        => $brand,
        'model'        => $model,
        'year'         => $year,
        'price'        => $price,
        'transmission' => $transmission,
        'photo'        => $namaPhoto
    ]
]);
