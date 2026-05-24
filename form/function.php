<?php
include('config.php');
session_start();
if(isset($_GET['logout']))
{
    session_unset();
    session_destroy();
    alert('Log Keluar Berjaya',"index.php");
}
if(isset($_POST['login_true']))
{
    //TODO: Need more security
    $username = $_POST['login_username'];
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
        alert('Log Masuk Berjaya','index.php');
    }
}
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
    function togglemodal(elementid)
    {
        const modal = document.getElementById(elementid);
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
        max-width: 600px;
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
        <a class="btn btn-danger" href="index.php?logout">Log Keluar</a>
        <?php
        ?><br><?php
    }
    else
    {
        //!password is filled with samplepassword
        ?>
        <form class="w-75 m-auto"action="index.php" method="post">
            <section class="row"><div class="col-12"><label for="login_username">Nama</label><input class="form-control" type="text" name="login_username" id="login_username"></div></section>
            <section class="row"><div class="col-12"><label for="login_password">Kata Laluan</label><input class="form-control" type="text" name="login_password" id="login_password" value="<?php echo($_ENV['SAMPLE_PASSWORD']);?>"></div></section>
            <section class="row"><div class="col-12"><input class="btn btn-primary" type="submit" name="login_true" value="Log Masuk"></div></section>
        </form>
        <?php
    }

    return ob_get_clean();
}
function mydate($datetime)
{
    return date('d/m/Y', strtotime($datetime));
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
function devicecount($data)
{
    global $asset_fields;
    ob_start();
    foreach(array_keys($asset_fields) as $asset_type)
    {
        if(isset($data["${asset_type}_count"]))
        {
            $label = ucwords($asset_type)." x ".$data["${asset_type}_count"]."\n";
            ?><code><?php echo($label);?></code><?php
        }
    }
    return ob_get_clean();
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