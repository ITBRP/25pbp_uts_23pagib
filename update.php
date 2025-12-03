<?php
header("Content-Type: application/json; charset=UTF-8");


if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit;
}


$raw = file_get_contents("php://input");
$data = json_decode($raw, true);


$errors = [];

if(!isset($data['brand'])){
    $errors['brand'] = "brand wajib dikirim";
}else{
    if($data['brand']==''){
        $errors['brand'] = "brand tidak boleh kosong";
    }else if(strlen($data['brand']) < 2){
        $errors['brand'] = "brand harus minimal 2 karakter!";
    }
}


if(!isset($data['model'])){
    $errors['model'] = "model wajib dikirim";
}else{
    if($data['model']==''){
        $errors['model'] = "model tidak boleh kosong";
    }if (!preg_match('/^[a-zA-Z0-9 ]+$/', $data['model'])) {
    $errors['model'] = "Model Tidak boleh karakter spesial!";
    }
}


if (!isset($data['year'])) {
    $errors['year'] = "year wajib dikirim";
} else {

    $year = trim($data['year']);
    $currentYear = date("Y");

    if ($year == '') {
        $errors['year'] = "year tidak boleh kosong";

    } else if (
        !preg_match('/^[0-9]{4}$/', $year) || $year < 1990 || $year > $currentYear              
    ) {
        $errors['year'] = "year harus 4 digit angka dan berada di antara 1990 - $currentYear";
    }
}

if(!isset($data['price'])){
    $errors['price'] = "price wajib dikirim";
}else{
    $price = trim($data['price']);
    if($data['price']==''){
        $errors['price'] = "price tidak boleh kosong";
    }else if (!preg_match('/^[0-9]+$/', $price) || (int)$price <= 0) {
        $errors['price'] = "price harus angka dan lebih dari 0";
    }
}

if(!isset($data['transmission'])){
    $errors['transmission'] = "transmission wajib dikirim";
}else{
     $transmission = trim($data['transmission']);
    if($data['transmission']==''){
        $errors['transmission'] = "transmission tidak boleh kosong";
    }else if (!in_array($transmission, ['manual', 'automatic'])) {
        $errors['transmission'] = "transmission harus 'manual' atau 'automatic'";
    }
}

$anyPhoto = false;
$namaPhoto = '';
if (isset($_FILES['photo'])) {

   
    if ($_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $fileName = $_FILES['photo']['name']; 
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); 

        if (!in_array($fileExt, $allowed)) {
            $errors['photo'] = "File harus jpg, jpeg atau png";
        } else {
            $anyPhoto = true; 
            $namaPhoto = md5(date('dmyhis')) . "." . $fileExt; 
        }
    }

}


if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "msg" => "Data error",
        "errors" => $errors
    ]);
    exit;
}


$cek = $koneksi->query("SELECT * FROM car WHERE id=$id");
if (!$cek || $cek->num_rows == 0) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg" => "Data not found"
    ]);
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "mobil");
$q = "
    UPDATE car SET
        brand = '".$koneksi->real_escape_string($data['brand'])."',
        model = '".$koneksi->real_escape_string($data['model'])."',
        year = '".$koneksi->real_escape_string($data['year'])."',
        price = '".$koneksi->real_escape_string($data['price'])."',
        transmission = '".$koneksi->real_escape_string($data['transmission'])."',
        photo = '".($data['photo'] ?? "")."'
    WHERE id = $id
";

if (!$koneksi->query($q)) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit;
}


http_response_code(200);

echo json_encode([
    "status" => "success",
    "msg" => "Process success",
    "data" => [
        "id" => (int) $id,
        "brand" => $data['brand'],
        "model" => $data['model'],
        "year" => $data['year'],
        "price" => (int)$data['price'],
        "transmission" => $data['transmission'],
        "photo" => $data['photo'] ?? null
    ]
]);