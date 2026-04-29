<?php
include('config.php');
include 'samplesession.php';
$content = '';

$stmt = $conn->prepare("SELECT * FROM user WHERE userid LIKE ?");
$stmt->bind_param("s",$_SESSION['userid']);
$stmt->execute();
$userdata = mysqli_fetch_assoc($stmt->get_result());
$content .= '<form class="w-75 m-auto" action="" method="post">';
$content .= '<h1>Permohonan Penggunaan Makmal Komputer</h1>';
$safeuid = htmlspecialchars($userdata['userid'], ENT_QUOTES, 'UTF-8');
$content .= "<input type='hidden' value='$safeuid'>";
$content .= "<input class='form-control' type='text' name='username' value='{$userdata['username']}'>";
$content .= '</form>';
?>
<?php include('layout.php');?>