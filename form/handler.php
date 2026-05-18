<?php
include('function.php');
if($_SESSION['usertype'] != 'handler')
{
    alert("Access Denied. Redirecting...", $returnpage);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if(isset($_POST['addasset']))
    {
        $assetid = $_POST['asset_id'];
        $assetlabel = $_POST['asset_label'];
        $assettype = $_POST['asset_type'];
        $handlerid = $_SESSION['userid'];
        $assetstatus = "good";
        $assetdetails = json_encode([
            "added" => date('m/d/Y H:i:s', time()),
            "updated" => date('m/d/Y H:i:s', time())
        ],JSON_PRETTY_PRINT);
        $stmt = $conn->prepare("INSERT INTO T2_asset (T2_assetid,T2_label,T2_type,T2T1_handlerid,T2_status,T2_details) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("ssssss",$assetid,$assetlabel,$assettype,$handlerid,$assetstatus,$assetdetails);
        if($stmt->execute())
        {
            $message = $_POST['asset_id'] . " added by " . $_SESSION['username'];
            alert($message,redirect:"./handler.php?asset"); 
        }
    }
    else if(isset($_POST['updateasset']))
    {
        $assetid = $_POST['asset_id'];
        $assetlabel = $_POST['asset_label'];
        $assettype = $_POST['asset_type'];
        $assetstatus = "good";
        $assetdetails = json_encode([
            "updated" => date('m/d/Y H:i:s', time())
        ],JSON_PRETTY_PRINT);
        $stmt = $conn->prepare("UPDATE T2_asset SET T2_label = ?, T2_type = ?, T2_status = ?, T2_details = ? WHERE T2_assetid = ?");
        $stmt->bind_param("ssss",$assetlabel,$assettype,$assetstatus,$assetdetails,$assetid);
        if($stmt->execute())
        {
            alert("Aset $assetlabel dikemaskini.", "handler.php?asset=".$assetid);
        }
    }
}
$content = '';
$title = 'Pengendalian';
$content .= nav();
if($_GET)
{
    if(isset($_GET['asset']) && isset($_GET['new']))
    {
        //TODO: new asset
        $title = "Tambah Aset";
        $content .= assetform();

    }
    else if(isset($_GET['asset']) && !empty($_GET['asset']))
    {
        //TODO: specific asset page  
        $title = "Kemasikini Aset";
        $content .= assetform($_GET['asset']);
    }
    else if(isset($_GET['asset']))
    {
        //TODO: List assets. KIV(sort filter)
        ob_start();?>
        <h1>Senarai Aset</h1>
        <table class="table table-bordered w-75 m-auto">
            <tr>
                <th>Label</th>
                <th>Jenis</th>
            </tr>
            <?php
            $stmt = $conn->prepare("SELECT * FROM T2_asset WHERE T2T1_handlerid = ?");
            $stmt->bind_param("s", $_SESSION['userid']);
            $stmt->execute();
            $data = $stmt->get_result();
            while($list = mysqli_fetch_assoc($data))
            {
                $details = json_decode($list['T2_details'],true);
                ?>
                <tr>
                    <td><a href="?asset=<?php echo($list['T2_assetid']);?>"><?php echo($list['T2_label']);?></a></td>
                    <td><?php echo($list['T2_type']);?></td>
                    <td><?php echo('details...');?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
        $content .= ob_get_clean(); 
    }
}
else
{
    ob_start();
        //Home page for handler.
    // TODO loan watch
    ?>
    <h1>Utama</h1>
    <?php
    $content .= ob_get_clean();
}
?>
<?php include('layout.php');?>
<?php
function assetform($assetid = null)
{
    global $asset_fields, $conn;
    ob_start();
    if($assetid!= null  )
    {
        ?><h1>Kemaskini Perihal Aset</h1><?php
        $stmt = $conn->prepare("SELECT * FROM T2_asset WHERE T2_assetid = ? LIMIT 1");
        $stmt->bind_param("s",$assetid);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0)
        {
            $asset = mysqli_fetch_assoc($result);
            $handlerid = $asset['T2T1_handlerid'];
            $assetlabel = $asset['T2_label'];
            $assettype = $asset['T2_type'];
            //$details = json_decode($asset['T2_details'],true);
            $btnsubmit = ['updateasset','Kemaskini'];
        }
    }
    else
    {
        ?><h1>Tambah Aset</h1><?php
        $handlerid = $_SESSION['userid'];
        $assetlabel = '';
        $assettype = '';
        $btnsubmit = ['addasset','Tambah'];
    }
    //T2_assetid, T2_label, T2_type, T2T1_handlerid, T2_status,T2_details
    ?>
    <form action="handler.php" method="post">
        <input class="form-control" type="text" name="asset_handlerid" value="<?php echo(e($_SESSION['userid']));?>" hidden>
        <input class="form-control" type="text" name="asset_id" id="" value="<?php echo($assetid);?>"placeholder="ID Aset" <?php if($assetid){ echo(' readonly');} ?>>
        <input class="form-control" type="text" name="asset_label" id="" value="<?php echo($assetlabel);?>"placeholder="Label">
        <div class="row">
            <div class="col-6">
                <select class="form-control" name="asset_type" id="asset_type" required>
                    <option value="">--- Sila pilih jenis aset ---</option>
                    <?php
                foreach(array_keys($asset_fields) as $field){
                    $type_label = ucfirst($field);
                    $type_value = preg_replace('/\s+/', '-', strtolower($field));
                    ?><option value="<?php echo($type_value);?>"><?php echo($type_label);if($assettype == $type_value){ echo(' selected'); } ?></option><?php
                }
                ?>
                </select>
            </div>
            <div class="col-6">
                <input class="btn btn-primary" name="<?php echo($btnsubmit[0]); ?>" type="submit" value="<?php echo($btnsubmit[1]); ?>">
            </div>
        </div>
        <h3>Butiran Aset</h3>
        <div id="details"></div>
        <script>//TODO: generate input field for details of asset type
            const asset_details = document.getElementById('details');
            const asset_type = document.getElementById('asset_type');
            asset_type.addEventListener('change', (event) => {
                const type = asset_type.value;
                asset_details.replaceChildren();
                asset_details.textContent += "Abaikan buat masa ini";
                Object.keys(assetFields[type]).forEach(detail => {
                    asset_details.textContent += detail;
                });
            });
        </script>
    </form>
    <?php
    return(ob_get_clean());
}