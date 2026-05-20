<?php
include('config.php');
session_start();
//!TEST start
//SAMPLE USER FOR EACH TYPE('manager','handler','staff','user') FOR TESTING PURPOSE ONLY. LOGIN SYSTEM NOT IMPLEMENTED YET.
//$samplepassword = password_hash($_ENV['SAMPLE_PASSWORD'], PASSWORD_DEFAULT);
/*
INSERT INTO T1_user (T1_userid,T1_username,T1_email,T1_passwordhash,T1_type,T1_group) VALUES
('samplemanager01','Sample Manager 1','samplemanager@mail.com','password','manager','samplegroup'),
('samplehandler01','Sample Handler 1','samplehandler@mail.com','password','handler','samplegroup'),
('samplestaff01','Sample Staff 1','samplestaff@mail.com','password','staff','samplegroup'),
('sampleuser01','Sample User 1','sampleuser@mail.com','password','user','samplegroup');
*/
session_unset();
session_destroy();
session_start();
$sampletype = 'manager';//change this for testing each user type.
$stmt = $conn->prepare("SELECT * FROM T1_user WHERE T1_type = ? LIMIT 1");
$stmt->bind_param("s", $sampletype);
$stmt->execute();
$userinfo = $stmt->get_result();
while($u_r = mysqli_fetch_assoc($userinfo))
{
    $_SESSION['userid'] = $u_r['T1_userid'];
    $_SESSION['username'] = $u_r['T1_username'];
    $_SESSION['usertype'] = $u_r['T1_type'];
    $_SESSION['usergroup'] = $u_r['T1_group'];
}
$stmt->close();
//! TEST end
date_default_timezone_set('Asia/Kuala_Lumpur');

$asset_fields = [
    "laptop" => [
        "brand" => "str",
        "ram_type" => "str",
        "ram_count" => "int",
        "usb_a_female_count" => "int",
        "hdmi_input" => "bool"
    ],
    "projector" => [
        "brand" => "str",
        "hdmi_input" => "bool",
        "vga_input" => "bool"
    ],
    "monitor" => [
        "brand" => "str",
        "hdmi_input" => "bool",
        "vga_input" => "bool"
    ],
    "personal computer" => [
        "brand" => "str",
        "ram_count" => "int"
    ]
];
?>
<script>
    const assetFields = <?php echo(json_encode($asset_fields)); ?>;
    function setdatetomorrow(inputfield){
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        inputfield.value = tomorrow.toISOString().split('T')[0];
    }
    function setmindatetomorrow(inputfield){
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        inputfield.setAttribute('min', tomorrow.toISOString().split('T')[0]);
    }
    function checkrequest(requestid)
    {
        const modal = document.getElementById('dialog_'+requestid);
        if(modal.open)
        {
            modal.close();
        }
        else
        {
            modal.showModal();
        }
    }
</script>
<style>
    dialog {
        border: none;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 500px;
    }
    dialog::backdrop {
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
        backdrop-filter: blur(4px);            /* Blurs the background content */
    }
</style>
<?php
$returnpage = 'index.php';//! Page to return for non logged in user.
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
function nav()
{
    ob_start();
    if(isset($_SESSION['usertype']))
    {
        if($_SESSION['usertype'] == 'handler')
        {
            $navlist = [['handler.php','Utama'],['handler.php?asset&new','Tambah Aset'],['handler.php?asset','Senarai Aset'],["handler.php?request","Senarai Pinjaman"]];
        }
        else if($_SESSION['usertype'] == 'manager')
        {
            $navlist = [['manager.php','Utama'],['manager.php?request','Senarai Permohonan']];
        }
        else if($_SESSION['usertype'] == 'staff')
        {
            $navlist = [['staff.php','Utama'],['staff.php?request&new','Permohonan Baru'],['staff.php?request','Senarai Permohonan']];
        }
        foreach($navlist as $navitem)
        {
            ?>
            <a class="btn btn-outline-primary" href="<?php echo($navitem[0]); ?>"><?php echo($navitem[1]); ?></a>
            <?php
        }
        ?>
        <a class="btn text-muted""><?php echo($_SESSION['username']); ?></a>
        <?php
        ?><br><?php
    }
    else
    {
        return '';
    }

    return ob_get_clean();
}
function mytime($datetime)
{
    return date('d/m/Y H:i:s', strtotime($datetime));
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
<!-- For switching user type during testing. Rework
    <div class="w-100 bg-dark text-ligh text-center">
        <form action="" method="post">
            <input type="text" name="current_url" id="current_url" hidden>
            <input class="btn btn-primary" type="submit" name="changetype" value="manager">
            <input class="btn btn-primary" type="submit" name="changetype" value="handler">
            <input class="btn btn-primary" type="submit" name="changetype" value="staff">
            <input class="btn btn-primary" type="submit" name="changetype" value="user">
            <p class="text-danger">CHANGE USER TYPE. DELETE THIS AFTER TEST.</p>
        </form>
        <script>
            document.getElementById('current_url').value = window.location.href;
        </script>
    </div>
-->