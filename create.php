<?php 
header("Content-Type: application/json; charset=UTF-8");
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(500);
    $res = [
        'status' => 'error',
        'msg' => 'server eror !'
    ];
    echo json_encode($res);
    exit();
}

// block jika method nya benar
$errors = [];



if (!isset($_POST['brand'])) {
    $errors['brand'] = "brand wajib dikirim";
} else {
    if ($_POST['brand'] == '') {
        $errors['brand'] = "brand tidak boleh kosong";
    } elseif (strlen($_POST['brand']) < 2) {
        $errors['brand'] = "brand minimal 2 karakter";
    }
}


if (!isset($_POST['model'])) {
    $errors['model'] = "model wajib dikirim";
} else {
    if ($_POST['model'] == '') {
        $errors['model'] = "model tidak boleh kosong";
    } elseif (!preg_match('/^[a-zA-Z0-9 ]+$/', $_POST['model'])) {
        $errors['model'] = "model tidak boleh mengandung karakter spesial";
    }
}


if (!isset($_POST['year'])) {
    $errors['year'] = "year wajib dikirim";
} else {
    if ($_POST['year'] == '') {
        $errors['year'] = "year tidak boleh kosong";
    } elseif (!preg_match('/^\d{4}$/', $_POST['year'])) {
        $errors['year'] = "Format tahun harus 4 digit";
    } else {
        $yearNow = date("Y");
        $year = intval($_POST['year']);
        if ($year < 1990 || $year > $yearNow) {
            $errors['year'] = "Tahun harus antara 1990 - $yearNow";
        }
    }
}


if (!isset($_POST['price'])) {
    $errors['price'] = "price wajib dikirim";
} else {
    if ($_POST['price'] == '') {
        $errors['price'] = "price tidak boleh kosong";
    } elseif (!preg_match('/^[0-9]+$/', $_POST['price'])) {
        $errors['price'] = "price harus berupa angka";
    } elseif (intval($_POST['price']) <= 0) {
        $errors['price'] = "price harus lebih dari 0";
    }
}


if (!isset($_POST['transmission'])) {
    $errors['transmission'] = "transmission wajib dikirim";
} else {
    $tr = strtolower($_POST['transmission']);
    if ($tr == '') {
        $errors['transmission'] = "transmission tidak boleh kosong";
    } elseif (!in_array($tr, ['manual', 'automatic'])) {
        $errors['transmission'] = "transmission hanya boleh manual atau automatic";
    }
}
$anyPhoto = false;
$namaPhoto = '';
if (isset($_FILES['photo'])) {

    // User memilih file
    if ($_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $fileName = $_FILES['photo']['name']; //namaaslifile.JPEG, docx
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // hasilnya jadi jpeg

        if (!in_array($fileExt, $allowed)) {
            $errors['photo'] = "File harus jpg, jpeg atau png";
        } else {
            $anyPhoto = true; // photo valid, siap disave
            $namaPhoto = md5(date('dmyhis')) . "." . $fileExt; // fjsadlfjiajflsdjflsadkjfsad.jpeg
        }
    }

}

if(count($errors)>0){
    http_response_code(400);
    $res = [
        'status' => 'error',
        'msg' => 'Data error',
        'errors' => $errors
    ];
    echo json_encode($res);
    exit();
}

// insert ke db
$koneksi = new mysqli('localhost', 'root','', 'db_salsa');
$brand = $_POST['brand'];
$model = $_POST['model'];
$year = $_POST['year'];
$price = $_POST['price'];
$transmission = $_POST['transmission'];

if ($anyPhoto) {
    move_uploaded_file($_FILES['photo']['tmp_name'], 'img/' . $namaPhoto);
}
$q = "INSERT INTO uts(brand, model, year, price, transmission, photo) VALUES ('$brand', '$model', '$year', '$price', '$transmission', " . ($namaPhoto ? "'$namaPhoto'" : "NULL") . ")";


$koneksi->query($q);
$id = $koneksi->insert_id;

http_response_code(201);
echo json_encode([
    'status' => "success",
    'msg' => "Process success",
    'data' => [
        'id' => $id,
        'brand' => $brand,
        'model' => $model,
        'year' => $year,
        'price' => $price,
        'transmission' => $transmission,
        'photo' => $namaPhoto
    ]
]);