<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    http_response_code(405);
    echo json_encode(["status" => "error", "msg" => " Server error"]);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "msg" => "ID required"]);
    exit();
}

$id = $_GET['id'];

$k = new mysqli("localhost", "root", "", "db_mobil");

// CEK DATA
$cek = $k->query("SELECT * FROM mobil WHERE id='$id'");
if ($cek->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["status" => "error", "msg" => "Data not found"]);
    exit();
}

$k->query("DELETE FROM mobil WHERE id='$id'");

echo json_encode([
    "status" => "success",
    "msg" => "Delete data success",
    "data" => ["id" => $id]
]);
