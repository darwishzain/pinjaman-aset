<?php
include('config.php');
include 'function.php';
$content = '';
//TODO: redirect out if not certain role
//! delete testing asset add: DELETE FROM T2_asset WHERE T2_label NOT LIKE '%Sample%';
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
            $message = $_POST['T2_assetid_add'] . " added by " . $_POST['T2_handlerid_add'];
            alert($message,redirect:"./asset.php");
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
                    <select class="form-control" name="T2_type_add" id="T2_type_add">
                    <?php
                    $type_fields = [
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
                    $inventory_fields = ['laptop','projector','monitor','personal  computer'];
                    foreach(array_keys($type_fields) as $field){
                        $label = ucfirst($field);
                        $value = preg_replace('/\s+/', '-', strtolower($field));
                        ?><option value="<?php echo($value);?>"><?php echo($label);?></option><?php
                    }
                    ?>
                    </select>
                </div>
                <!-- <div class="col-3"> -->
                    <!-- <input type="text" name="asset" id=""> -->
                    <!-- <input class="form-control" name="bd-assetinventory" type="number" id="" value="0"> -->
                <!-- </div> -->
                <div class="col-6">
                    <input class="form-control" name="asset_add" type="submit" value="Tambah">
                </div>
                <div id="details"></div>
                <script>
                    const type_fields = <?= json_encode($type_fields) ?>;
                    const dropdown = document.getElementById('T2_type_add');
                    dropdown.addEventListener('change', (event) => {
                        detaildiv = document.getElementById('details');
                        detaildiv.replaceChildren();
                        const type = event.target.value;
                        details_field = Array(type_fields[type]);
                        details_field.forEach(detail => {
                            //console.log(type_fields[type]);
                            console.log(detail);
                        });
                        console.log("Selected:", type);
                    });
                </script>
                
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
    <h1>Senarai Aset <a href="?asset">+</a></h1>
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
            $details = json_decode($list['T2_details'],true);
            ?>
            <tr>
                <td><?php echo($list['T2_label']);?></td>
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
?>
<?php include('layout.php');?>