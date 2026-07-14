<?php
include('function.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    
}

$content = '';
$title = 'Home';
$content .= nav();
if(isset($_SESSION['userid']))
{
    ob_start();
    ?>
    <?php
    $content .= ob_get_clean();
}
else
{
    ob_start();
    ?>
    
    <?php
    $content .= ob_get_clean();
}
if($_GET)
{
    
}
else
{
    
    //$content .= $_SESSION['username'] . " (" . $_SESSION['usertype'] . ")";
}

?>
<?php include('layout.php');?>
