<?php
include('config.php');
include 'function.php';
$content = '';
//TODO: redirect if not certain role
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['bd-addasset'])
    {
        
    }
}
if($_GET)
{
    if(isset($_GET['add']))
    {
        $title = "Tambah Aset";
        ob_start();//Using output buffering?>
        <form class="w-75 m-auto" action="" method="post">
            <h1>Tambah Aset</h1>
            <input class="form-control" name="userid" type="text" hidden>
            <input class="form-control" name="bd-assethandler" type="text" value="<?php echo(e($_SESSION['userid']));//set handler be current user?>" hidden>
            <input class="form-control" type="text" name="bd-assetid" id="" placeholder="ID Aset">
            <input class="form-control" type="text" name="bd-assetlabel" id="" placeholder="Label">
            <div class="row">
                <div class="col-6">
                    <select class="form-control" name="bd-assettype" id="">
                        <option value="laptop">Laptop</option>
                        <option value="projector">Projektor</option>
                        <option value="monitor">Monitor</option>
                    </select>
                </div>
                <div class="col-3">
                    <input class="form-control" name="bd-assetinventory" type="number" id="" value="0">
                </div>
                <div class="col-3">
                    <input class="form-control" name="bd-addasset" type="submit" value="Tambah">
                </div>
            </div>

            
        </form>
        <?php
        $content .= ob_get_clean();
    }
    else if(isset($_GET['asset']))
    {
        //List assets(?)
    }
}
?>
<?php include('layout.php');?>