<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json; charset=UTF-8");

// ✅ HARUS PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'msg' => 'Server error'
    ]);
    exit();
}

// ✅ CEK ID
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'msg' => 'ID wajib dikirim'
    ]);
    exit();
}

$id = $_GET['id'];

// ✅ AMBIL JSON BODY
$data = json_decode(file_get_contents("php://input"), true);

$brand = $data['brand'];
$model = $data['model'];
$year = $data['year'];
$price = $data['price'];
$transmission = $data['transmission'];
$photo = $data['photo'];

// ✅ KONEKSI DB
$koneksi = new mysqli('localhost', 'root', '', 'db_uts');

if ($koneksi->connect_error) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Gagal konek DB'
    ]);
    exit();
}

// ✅ QUERY UPDATE
$q = "UPDATE tabel_vera SET
        brand='$brand',
        model='$model',
        year='$year',
        price='$price',
        transmission='$transmission',
        photo='$photo'
      WHERE id='$id'";

$update = $koneksi->query($q);

// ✅ RESPONSE
if ($update) {
    echo json_encode([
        'status' => 'success',
        'msg' => 'Data berhasil diupdate'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'msg' => $koneksi->error
    ]);
}
?>
