<?php
session_start();
$stmt = $conn->prepare("SELECT * FROM T1_user LIMIT 1");
$stmt->execute();
$userinfo = $stmt->get_result();
while($u_r = mysqli_fetch_assoc($userinfo))
{
    $_SESSION['userid'] = $u_r['T1_userid'];
}
$stmt->close();

date_default_timezone_set('Asia/Kuala_Lumpur');

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
function alert($message,$redirect = null)
{
    ?>
    <script>
    <?php
    echo("alert('$message');");
    if($redirect)
    {
        echo("window.location.href = '$redirect';");
    }
    ?>
    </script>
    <?php
    //exit;
}
?>