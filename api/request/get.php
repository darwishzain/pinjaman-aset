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

if($_GET)
{
    if(isset($_GET['request']) && !empty($_GET['request']))
    {
        /*
        SELECT 
            r.*,
            u.T1_userid,
            u.T1_username,
            r.T3_managerapprove->>'$.managerid' AS extracted_manager_id,
            m.T1_userid AS manager_user_id,
            m.T1_username AS manager_username
        FROM T3_request r
        INNER JOIN T1_user u ON r.T3T1_userid = u.T1_userid
        LEFT JOIN T1_user m ON r.T3_managerapprove->>'$.managerid' = m.T1_userid
        WHERE r.T3_requestid = ?;
        */
        $stmt = $conn->prepare("
        SELECT r.*,u.T1_userid,u.T1_username FROM T3_request r
        INNER JOIN T1_user u ON r.T3T1_userid = u.T1_userid
        WHERE T3_requestid = ?
        ");
        $stmt->bind_param("s",$_GET['request']);
        if($stmt->execute())
        {
            $response['status'] = "success";
            $r = mysqli_fetch_assoc($stmt->get_result());
            $response['data'] = $r;
        }
        else
        {
            $response['status'] = "empty";
        }
        $stmt->close();
    }
}
$response["usertype"] = $_SESSION['usertype'];
echo json_encode($response);
?>