<?php 
header("Content-Type: application/json; charset=UTF-8");
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(405);
    $res = [
        'status' => 'error',
        'msg' => 'Method error !'
    ];
    echo json_encode($res);
    exit();
}

// block jika method nya benar
$errors = [];
if(!isset($_POST['brand'])){
    $errors['brand'] = "brand wajib dikirim";
}else{
    if($_POST['brand']==''){
        $errors['brand'] = "brand tidak boleh kosong";
    }else if(strlen($_POST['brand']) < 2){
        $errors['brand'] = "brand harus minimal 2 karakter!";
    }
}

if(!isset($_POST['model'])){
    $errors['model'] = "model wajib dikirim";
}else{
    if($_POST['model']==''){
        $errors['model'] = "model tidak boleh kosong";
    }if (!preg_match('/^[a-zA-Z0-9 ]+$/', $_POST['model'])) {
    $errors['model'] = "Model Tidak boleh karakter spesial!";
    }
}

if (!isset($_POST['year'])) {
    $errors['year'] = "year wajib dikirim";
} else {

    $year = trim($_POST['year']);
    $currentYear = date("Y");

    if ($year == '') {
        $errors['year'] = "year tidak boleh kosong";

    } else if (
        !preg_match('/^[0-9]{4}$/', $year) || $year < 1990 || $year > $currentYear              
    ) {
        $errors['year'] = "year harus 4 digit angka dan berada di antara 1990 - $currentYear";
    }
}


if(!isset($_POST['price'])){
    $errors['price'] = "price wajib dikirim";
}else{
    $price = trim($_POST['price']);
    if($_POST['price']==''){
        $errors['price'] = "price tidak boleh kosong";
    }else if (!preg_match('/^[0-9]+$/', $price) || (int)$price <= 0) {
        $errors['price'] = "price harus angka dan lebih dari 0";
    }
}

if(!isset($_POST['transmission'])){
    $errors['transmission'] = "transmission wajib dikirim";
}else{
     $transmission = trim($_POST['transmission']);
    if($_POST['transmission']==''){
        $errors['transmission'] = "transmission tidak boleh kosong";
    }else if (!in_array($transmission, ['manual', 'automatic'])) {
        $errors['transmission'] = "transmission harus 'manual' atau 'automatic'";
    }
}

$anyPhoto = false;
$namaPhoto = 'null';
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
            $namaPhoto = md5(date('dmyhis')) . "." . $fileExt; 
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
if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}
$brand = $_POST['brand'];
$model = $_POST['model'];
$year = $_POST['year'];
$price = $_POST['price'];
$transmission = $_POST['transmission'];

if ($anyPhoto) {
    move_uploaded_file($_FILES['photo']['tmp_name'], 'img/' . $namaPhoto);
}
$q = "INSERT INTO uts(brand, model, year, price, transmission, photo) VALUES ('$brand', '$model', '$year', '$price','$transmission', " . ($namaPhoto ? "'$namaPhoto'" : "NULL") . ")";


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