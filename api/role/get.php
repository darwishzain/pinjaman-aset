<?php
include('../config.php');
header('Content-Type: application/json');
session_start();

// 1. THE BOUNCER: Check if the user is logged in and is an Admin
if (!isset($_SESSION['userid']) || ($_SESSION['userrole'] !== 'superadmin' && $_SESSION['userrole'] !== 'admin')) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Tidak dibenarkan. Hanya pengurus dan pegawai Bahagian Digital dibenarkan']);
    exit;
}
$response = [
    "status"    => "success",
    "code"      => 200,
    "message"   => "Roles retrieved successfully",
    "timestamp" => date('Y-m-d H:i:s'),
    "data"      => [] // Initialize empty array to prevent issues if no rows are found
];
$stmt = $conn->prepare("SELECT * FROM T2_role");
$stmt->execute();
$rolelist = $stmt->get_result();
$response['status'] = "success";
$response['data'] = [];
while($role = mysqli_fetch_assoc($rolelist))
{
    $response['data'][] = $role;
}
echo(json_encode($response));
