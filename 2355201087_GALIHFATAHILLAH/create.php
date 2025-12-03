<?php
header("Content-Type: application/json; charset=UTF-8");

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode(["status"=>"error","msg"=>"Method salah"]);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);

$errors = [];

// BRAND
if(!isset($input['brand']) || strlen(trim($input['brand'])) < 2){
    $errors['brand'] = "Brand minimal 2 karakter";
}

// MODEL
if(!isset($input['model']) || !preg_match('/^[a-zA-Z0-9 ]+$/', $input['model'])){
    $errors['model'] = "Model tidak valid";
}

// YEAR (YEAR TYPE)
if(!isset($input['year']) || !preg_match('/^[0-9]{4}$/', $input['year']) ||
   $input['year'] < 1990 || $input['year'] > date("Y")){
    $errors['year'] = "Tahun tidak valid (1990 - " . date("Y") . ")";
}

// PRICE
if(!isset($input['price']) || !is_numeric($input['price']) || $input['price'] <= 0){
    $errors['price'] = "Harga tidak valid";
}

// TRANSMISSION
if(!isset($input['transmission']) || !in_array($input['transmission'], ['Manual','Automatic'])){
    $errors['transmission'] = "Transmission tidak valid";
}

// PHOTO (BASE64)
$photo = null;
if(isset($input['photo']) && $input['photo'] !== ""){
    if(preg_match('/^data:image\/(jpg|jpeg|png);base64,/', $input['photo'])){
        $photo = $input['photo'];
    } else {
        $errors['photo'] = "Format foto harus base64 (jpg/jpeg/png)";
    }
}

if(count($errors) > 0){
    http_response_code(400);
    echo json_encode(["status"=>"error","msg"=>"Data error","errors"=>$errors]);
    exit();
}

$k = new mysqli("localhost","root","","uts_mobil");

$brand = $input['brand'];
$model = $input['model'];
$year = $input['year'];
$price = $input['price'];
$trans = $input['transmission'];

$q = "INSERT INTO mobil (brand, model, year, price, transmission, photo)
      VALUES ('$brand','$model','$year','$price','$trans',".($photo ? "'".$k->real_escape_string($photo)."'" : "NULL").")";

$k->query($q);
$id = $k->insert_id;

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
        "photo"=>$photo
    ]
]);
