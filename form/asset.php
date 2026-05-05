<?php
include('config.php');
include 'function.php';
$content = '';
//TODO: redirect out if not certain role
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['asset_add']))
    {
        $timestamp = date('m/d/Y H:i:s', time());
        $details = json_encode([
            "added" => $timestamp
        ],JSON_PRETTY_PRINT);
        //$details = json_encode($detailsdata,JSON_PRETTY_PRINT);
        $handlerid = $_POST['T2_handlerid_add'];//* For now be the id of current user(Only allow BD staff)
        $assetid = $_POST['T2_assetid_add'];
        $assetlabel = $_POST['T2_label_add'];
        $assettype = $_POST['T2_type_add'];
        $assetstatus = "good";
        $stmt = $conn->prepare("INSERT INTO T2_asset (T2_assetid,T2_label,T2_type,T2_handlerid,T2_status,T2_details) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("ssssss",$assetid,$assetlabel,$assettype,$handlerid,$assetstatus,$details);
        if($stmt->execute())
        {
            header('asset.php');
            alert($_POST['T2_assetid_add'] . " added by " . $_POST['T2_handlerid_add'] );
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
            <input class="form-control" name="T2_handlerid_add" type="text" value="<?php echo(e($_SESSION['userid']));//set handler be current user?>" hidden>
            <input class="form-control" type="text" name="T2_assetid_add" id="" placeholder="ID Aset">
            <input class="form-control" type="text" name="T2_label_add" id="" placeholder="Label">
            <div class="row">
                <div class="col-6">
                    <select class="form-control" name="T2_type_add" id="">
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
                    <input class="form-control" name="asset_add" type="submit" value="Tambah">
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
    <table class="table table-bordered w-75 m-auto">
        <tr>
            <th>Label</th>
            <th>Jenis</th>
        </tr>
        <?php
        $stmt = $conn->prepare("SELECT * FROM T2_asset");
        $stmt->execute();
        $data = $stmt->get_result();
        while($list = mysqli_fetch_assoc($data))
        {
            ?>
            <tr>
                <td><?php echo($list['T2_label']);?></td>
                <td><?php echo($list['T2_type']);?></td>
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