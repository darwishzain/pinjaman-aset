<?php
include('config.php');
include 'function.php';
$content = '';
$title = 'Peminjaman';
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if(isset($_POST['userid_user']))
    {
        $userid = $_POST['userid_user'];
        alert("Redirecting as User with ID: $userid", "lend.php?request");
    }
    else if(isset($_POST['submitlendrequest']))
    {
        $userid = $_POST['T1_userid'];
        $reason = $_POST['T3_reason'];
        $remark = $_POST['T3_remark'];
        $purpose = $_POST['T3_purpose'];
        $submittime = date('m/d/Y H:i:s', time());
        $detailsdata['location'] = $_POST['T3_location'];
        $detailsdata['datetoreceive'] = $_POST['T3_datetoreceive'];
        foreach (['laptop','projector','monitor','personal computer'] as $assettype) {
        {
            $value = preg_replace('/\s+/', '-', strtolower($assettype));
            $assetcount = $_POST[$value];
            if($assetcount > 0)
            {
                $detailsdata[$assettype] = $assetcount;
            }
        }
        }
        alert($_POST['T1_reason'], "lend.php?new");
    }
}

if($_GET)
{
    if(isset($_GET['request']) && empty($_GET['request'] ))
    {
        ob_start();
        ?>
        <h1>Borang Penggunaan Perlalatan Komputer</h1>
        <form action="" method="post">
            <input class="form-control" type="text" name="T1_userid" id="" value="<?php echo(e($_SESSION['userid']));?>" hidden>
            <?php
            $stmt = $conn->prepare("SELECT T1_username,T1_role FROM T1_user WHERE T1_userid = ?");
            $stmt->bind_param("i", $_SESSION['userid']);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            ?>
            <h3><?php echo($user['T1_username'].'('.ucwords($user['T1_role']).')'); ?></h3>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-4">
                            <label class="form-label">Tempat Penggunaan</label>
                            <input class="form-control" type="text" name="T3_location" id="" value="Sample Location" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Tarikh untuk Ambil</label>
                            <input class="form-control" type="date" name="T3_datetoreceive" id="T3_datetoreceive" value="" required>
                            <script>
                            const date = new Date();
                            date.setDate(date.getDate() + 1);
                            const tomorrow = date.toISOString().split('T')[0];
                            document.getElementById('T3_datetoreceive').value = tomorrow;
                            document.getElementById('T3_datetoreceive').setAttribute('min', tomorrow);
                            </script>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Kegunaan</label>
                            <select class="form-control" name="T3_purpose" id="">
                                <option value="individual">Individu</option>
                                <option value="department">Bahagian/Jabatan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Tujuan *</label>
                            <textarea class="form-control" name="T3_reason" id="T3_reason" cols="30" rows="5" placeholder="Tujuan" style="resize: none;" required></textarea>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Catatan (Jika Perlu)</label>
                            <textarea class="form-control" name="T3_remark" id="T3_remark" cols="30" rows="5" placeholder="Catatan (Jika Perlu)" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <label for="T3_type">Jenis Permohonan</label>
                <select class="form-control" name="T3_type" id="" required readonly>
                    <?php
                        //! Readonly as currently is asset borrow only
                        $stmt = $conn->prepare("SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_NAME = 'T3_request' AND COLUMN_NAME = 'T3_type'");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        //$requesttype = trim($row['COLUMN_TYPE'], "enum()");
                        $requesttype = str_getcsv(preg_replace('/^enum\((.*)\)$/i', '$1', $row['COLUMN_TYPE']),",","'");
                        foreach ($requesttype as $index => $type) {
                            $selected = ($type == 'asset') ? 'selected' : '';
                            $value = $index+1;
                            $label = ucwords($type);
                            echo "<option value='$value' $selected>$label</option>";
                        }
                    ?>
                </select>
            </div>
            <h4>Butiran Aset</h4>
            <?php
            foreach (array_keys($type_fields) as $assettype) {
                $label = ucwords($assettype);
                $value = preg_replace('/\s+/', '-', strtolower($assettype));
                ?>
                <div class="row w-50 m-auto">
                    <div class="col-6">
                        <label for="<?php echo($value); ?>"><?php echo($label); ?></label>
                    </div>
                    <div class="col-6">
                        <input type="number" name="<?php echo($value); ?>" id="<?php echo($value); ?>" class="form-control" value="0">
                    </div>
                </div>
                <?php
            }
            ?>
            <input class="btn btn-primary" type="submit" name="submitlendrequest" value="Hantar Permohonan" >
        </form>
        <?php
        $content .= ob_get_clean();
    }
    else if(isset($_GET['request']) && !empty($_GET['request']))
    {
        //If asset or facility
        ob_start();

        $content .= ob_get_clean();
    }
}
else
{

}
//Status:
//PENDING_MANAGER
//REJECTED
//PENDING_HANDLER
//READY_FOR_HANDOVER
//BORROWED
//RETURNED
//CANCELLED
//OVERDUE
?>
<?php include('layout.php');?>