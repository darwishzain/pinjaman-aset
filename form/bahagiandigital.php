<?php
include('config.php');
include 'samplesession.php';
$content = '';
$stmt = $conn->prepare("SELECT * FROM user WHERE userid LIKE ?");
$stmt->bind_param("s",$_SESSION['userid']);
$stmt->execute();
$userdata = mysqli_fetch_assoc($stmt->get_result());
$userid_s = htmlspecialchars($userdata['userid'], ENT_QUOTES, 'UTF-8');
if(!empty($_GET))
{
    if(isset($_GET['rent']) && $_GET['rent'] === 'new')
    {
        $content .= "<form class='w-75 m-auto' action='' method='post'>";
        $content .= "<h1>Permohonan Sewaan Alatan Komputer</h1>";
        $content .= "<input type='hidden' value='$userid_s'>";
        $content .= "Nama Pemohon: <input class='form-control' type='text' name='username' value='{$userdata['username']}'>";
        //GET Jabatan/Syarikat by userid
        $content .= "Tujuan: <input class='form-control' name='reason' type='text' placeholder='Tujuan'>";
        $content .= "<input class='form-control' name='datetimestart' id='' type='datetime-local' value=''>";
        $content .= "<input class='form-control' name='datetimeend' id='' type='datetime-local' value=''>";
        $content .= "Catatan (Jika Perlu): <input class='form-control' name='remark' type='text' placeholder='Catatan (Jika Perlu)'>";
        $content .= "<h2 class='text-center'>Kegunaan</h2>";
        $content .= "<div class='form-check'>";
        $content .= "    <input type='radio' class='form-check-input' id='radio1' name='usage' value='individual' checked>Individu";
        $content .= "    <label class='form-check-label' for='radio1'></label>";
        $content .= "</div>";
        $content .= "<div class='form-check'>";
        $content .= "    <input type='radio' class='form-check-input' id='radio1' name='usage' value='department'>Bahagians";
        $content .= "    <label class='form-check-label' for='radio1'></label>";
        $content .= "</div>";
        $content .= "</form>";
    }
    else if(isset($_GET['book']) && $_GET['book'] === 'new')
    {
                $content .= "<form class='w-75 m-auto' action='' method='post'>";
        $content .= "<h1>Permohonan Sewaan Alatan Komputer</h1>";
        $content .= "<input type='hidden' value='$userid_s'>";
        $content .= "Nama Pemohon: <input class='form-control' type='text' name='username' value='{$userdata['username']}'>";
        //GET Jabatan/Syarikat by userid
        $content .= "Tujuan: <input class='form-control' name='reason' type='text' placeholder='Tujuan'>";
        $content .= "<input class='form-control' name='datetimestart' id='' type='datetime-local' value=''>";
        $content .= "<input class='form-control' name='datetimeend' id='' type='datetime-local' value=''>";
        $content .= "Catatan (Jika Perlu): <input class='form-control' name='remark' type='text' placeholder='Catatan (Jika Perlu)'>";
        $content .= "<h2 class='text-center'>Kegunaan</h2>";
        $content .= "<div class='form-check'>";
        $content .= "    <input type='radio' class='form-check-input' id='radio1' name='usage' value='individual' checked>Individu";
        $content .= "    <label class='form-check-label' for='radio1'></label>";
        $content .= "</div>";
        $content .= "<div class='form-check'>";
        $content .= "    <input type='radio' class='form-check-input' id='radio1' name='usage' value='department'>Bahagians";
        $content .= "    <label class='form-check-label' for='radio1'></label>";
        $content .= "</div>";
        $content .= "</form>";
    }

}
else
{
    $content .= "<h1>Borang Bahagian Digital</h1>";
    $content .= "<h3><a href='?book=new'>Penggunaan Makmal Komputer</a></h3>";
    $content .= "<h3><a href='?rent=new'>Sewa Alatan Komputer</a></h3>";

}

?>
<?php include('layout.php');?>