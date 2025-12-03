<?php 

header("Content-Type: application/json; charset=UTF-8");


if($_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(500);
    $res = [
        'status' => 'error',
        'msg' => 'server error !'
    ];
    echo json_encode($res); //konversi string jadi json
    exit();
}

// block jika method nya benar
$json = file_get_contents("php://input");
$data = json_decode($json, true);

$errors = [];

if(!isset($data['brand'])){
    $errors['brand'] = "brand harus diisi!";
}else{
    if($data['brand']==''){
        $errors['brand'] = "brand tidak boleh kosong !!!";
    }elseif(strlen($data['brand'])<2){
        $errors['brand'] = "brand minimal 2 karakter";
    }
}

if(!isset($data['model'])){
    $errors['model'] = "model harus diisi!";
}else{
    if($data['model']==''){
        $errors['model'] = "model tidak boleh kosong !!!";
    }elseif(!preg_match('/^[A-Za-z ]+$/', $data['model'])){
        $errors['model'] = "model tidak boleh ada karakter spesial";
    }
}

if (!isset($data['year']) || trim($data['year']) === '') {
    $errors['year'] = "Format tahun tidak valid";
} else {
    if (!preg_match('/^[0-9]{4}$/', $data['year'])) {
        $errors['year'] = "Format tahun tidak valid";
    }
}

if (!isset($data['price']) || trim($data['price']) === '') {
    $errors['price'] = "Harus berupa angka dan lebih dari 0";
} else {
    if (!is_numeric($data['price']) || $data['price'] <= 0) {
        $errors['price'] = "Harus berupa angka dan lebih dari 0";
    }
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== 0) {
    $errors['photo'] = "Format file tidak valid (hanya jpg, jpeg, png)";
} else {
    $allowed = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $errors['photo'] = "Format file tidak valid (jpg, jpeg, png)";
    }
}

if(count($errors)>0){
    http_response_code(400);
    $res = [
        'status' => 'error',
        'msg' => 'data error!',
        'errors' => $errors
    ];
    echo json_encode($res);
    exit();
}

// insert ke db
$koneksi = new mysqli('localhost', 'root', '', 'data_mobil');
$brand = $data['brand'];
$model = $data['model'];
$year = $data['year'];
$price = $data['price'];
$transmission = $data['transmission'];
$photo = $data['photo'];
$q = "INSERT INTO table_datamobil(brand, model, year, price, transmission, photo) VALUES('$brand', '$model', '$year', '$price', '$transmission', '$photo')";
$sukses = $koneksi->query($q);
$id = $koneksi->insert_id;


echo json_encode([
    'status' => "success",
    'msg' => "proses sukses",
    'data' => [
        'brand' => $brand,
        'model' => $model,
        'year' => $year,
        'price' => $price,
        'transmission' => $transmission,
        'photo' => $photo
    ]
]);


// http_response_code(500);

?>