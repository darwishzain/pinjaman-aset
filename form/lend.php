<?php
include('config.php');
include 'function.php';
$content = '';
$title = 'Peminjaman';
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if(isset($_POST['submitlendrequest']))
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
    if(isset($_GET['new']))
    {
        ob_start();
        ?>
        <h1>Peminjaman</h1>
        <form action="" method="post">
            <input class="form-control" type="text" name="T1_userid" id="" value="<?php echo(e($_SESSION['userid']));?>" hidden>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Peminjam</label>
                            <input class="form-control" type="text" name="T1_name" id="" value="Sample Name" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Jabatan/Syarikat</label>
                            <input class="form-control" type="text" name="T1_department" id="" value="Sample Department/Company" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-4">
                            <label class="form-label">Tempat Penggunaan</label>
                            <input class="form-control" type="text" name="T3_location" id="" value="Sample Location" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Tarikh untuk Ambil</label>
                            <input class="form-control" type="date" name="T3_datetoreceive" id="" value="" required>
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
            <h4>Butiran Aset</h4>
            <?php
            foreach (['laptop','projector','monitor','personal computer'] as $assettype) {
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