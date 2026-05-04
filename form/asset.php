<?php
include('config.php');
include 'function.php';
$content = '';
//TODO: redirect out if not certain role
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['bd-addasset']))
    {
        $handlerid = $_POST['bd-assethandler'];
        $assetid = $_POST['bd-assetid'];
        $assetlabel = $_POST['bd-assetlabel'];
        $assettype = $_POST['bd-assettype'];
        $assetstatus = "good";
        $stmt = $conn->prepare("INSERT INTO bd_asset (assetid,label,type,handlerid,status) VALUES(?,?,?,?,?)");
        $stmt->bind_param("sssss",$assetid,$assetlabel,$assettype,$handlerid,$assetstatus);
        if($stmt->execute())
        {
            header('asset.php');
            alert($_POST['bd-assetid'] . " added by " . $_POST['bd-assethandler']);
        }
        $stmt->close();
    }
}
if($_GET)
{
    if(isset($_GET['asset']) && empty($_GET['asset']))
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
                    <input type="text" name="asset" id="">
                    <!-- <input class="form-control" name="bd-assetinventory" type="number" id="" value="0"> -->
                </div>
                <div class="col-3">
                    <input class="form-control" name="bd-addasset" type="submit" value="Tambah">
                </div>
                <div id="details">
                    Details
                </div>
            </div>
        </form>
        <?php
        $content .= ob_get_clean();
    }
    else if(isset($_GET['asset']))
    {
        //List assets(?)
        ob_start();

        $content .= ob_get_clean();
    }
}
else{
    ob_start();?>
    <h1>Senarai Aset</h1>
    <table class="table w-75 m-auto">
        <tr>
            <th>Label</th>
            <th>Jenis</th>
        </tr>
        <?php
        $stmt = $conn->prepare("SELECT * FROM bd_asset");
        $stmt->execute();
        $data = $stmt->get_result();
        while($list = mysqli_fetch_assoc($data))
        {
            ?>
            <tr>
                <td><?php echo($list['label']);?></td>
                <td><?php echo($list['type']);?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
    $content .= ob_get_clean();
}
?>
<?php include('layout.php');?>