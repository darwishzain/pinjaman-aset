<?php
include('config.php');
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();
if(isset($_GET['logout']))
{
    session_unset();
    session_destroy();
    //alert('Log Keluar Berjaya',"index.php");
}
if(isset($_POST['login_true']))
{
    //TODO: Need more security
    $username = $_POST['login_id'];
    $password = $_POST['login_password'];
    $stmt = $conn->prepare("SELECT * FROM T1_user WHERE T1_userid = ?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $login_r = mysqli_fetch_assoc($stmt->get_result());
    if (password_verify($password, $login_r['T1_passwordhash']))
    {
        $_SESSION['userid'] = $login_r['T1_userid'];
        $_SESSION['username'] = $login_r['T1_username'];
        $_SESSION['usertype'] = $login_r['T1_type'];
        $_SESSION['usergroup'] = $login_r['T1_group'];
        $_SESSION['userrole'] = $login_r['T1_role'];
        //alert('Log Masuk Berjaya','index.php');
    }
}
?>
<style>
    dialog {
        border: none;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 600px;
    }
    dialog::backdrop {
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
        backdrop-filter: blur(4px);            /* Blurs the background content */
    }
</style>
<?php
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>