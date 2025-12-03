<?php
header("Content-Type: application/json");

// METHOD CHECK (GET ONLY)
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Server error"
    ]);
    exit();
}

// CEK ID
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "msg" => "ID required"
    ]);
    exit();
}

$id = $_GET['id'];

// KONEKSI DB
$k = new mysqli("localhost", "root", "", "uts_mobil");

// QUERY
$q = "SELECT * FROM mobil WHERE id='$id'";
$res = $k->query($q);

// ERROR 500 jika query gagal


// DATA TIDAK DITEMUKAN
if ($res->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "msg" => "Data not found"
    ]);
    exit();
}

// SUCCESS
http_response_code(200);
echo json_encode([
    "status" => "success",
    "msg" => "Process success",
    "data" => $res->fetch_assoc()
]);
