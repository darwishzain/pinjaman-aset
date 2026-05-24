<?php
include('../config.php');
header('Content-Type: application/json');
session_start();

// 1. THE BOUNCER: Check if the user is logged in and is an Admin
if (!isset($_SESSION['userid']) || ($_SESSION['usertype'] !== 'handler' && $_SESSION['usertype'] !== 'manager')) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Tidak dibenarkan. Hanya pengurus dan pegawai Bahagian Digital dibenarkan']);
    exit;
}
$response["usertype"] = $_SESSION['usertype'];
echo json_encode($response);
?>