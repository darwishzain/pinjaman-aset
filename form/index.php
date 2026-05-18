<?php
include('function.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST')
{

}

$content = '';
$title = 'Home';
$content .= nav();
if($_GET)
{
    
}
else
{
    // To add computer lab bookings
    $content .= $_SESSION['username'] . " (" . $_SESSION['usertype'] . ")";
}

?>
<?php include('layout.php');?>
