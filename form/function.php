<?php
session_start();
$stmt = $conn->prepare("SELECT * FROM user LIMIT 1");
$stmt->execute();
$userinfo = $stmt->get_result();
while($u_r = mysqli_fetch_assoc($userinfo))
{
    $_SESSION['userid'] = $u_r['userid'];
}
$stmt->close();

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
function alert($string)
{
    echo("<script>alert($string);</script>");
}
?>