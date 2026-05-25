<?php include('function.php'); ?>
<?php
if($_SESSION['usertype'] != 'manager')
{
    alert("Access Denied. Redirecting...", $returnpage);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if(isset($_POST['managerapprove_true']))
    {
        $requestid = $_POST['managerapprove_requestid'];
        $approvaldata['managerid'] = $_POST['managerapprove_managerid'];
        $approvaldata['time'] = date(' Y-m-d H:i:s', time());
        $approvaldata['comment'] = $_POST['managerapprove_comment'];
        $status = "PENDING_HANDLER_APPROVAL";
        $managerapproval = json_encode($approvaldata);
        $stmt = $conn->prepare("UPDATE T3_request SET T3_managerapprove = ?,T3_status = ? WHERE T3_requestid = ?");
        $stmt->bind_param("sss",$managerapproval,$status,$requestid);
        if($stmt->execute())
        {
            $stmt->close();
            alert("Permohonan Disahkan","manager.php");
        }
    }
    else if($_POST['managerapprove_false'])
    {
        $requestid = $_POST['managerapprove_requestid'];
        $approvaldata['managerid'] = $_POST['managerapprove_managerid'];
        $approvaldata['time'] = date(' Y-m-d H:i:s', time());
        $approvaldata['comment'] = $_POST['managerapprove_comment'];
        $status = "MANAGER_REJECT";
        $managerapproval = json_encode($approvaldata);
        $stmt = $conn->prepare("UPDATE T3_request SET T3_managerapprove = ?,T3_status = ? WHERE T3_requestid = ?");
        $stmt->bind_param("sss",$managerapproval,$status,$requestid);
        if($stmt->execute())
        {
            $stmt->close();
            alert("Permohonan Ditolak","manager.php");
        }
    }
}

$content = '';
$title = 'Pengurus';
$content .= nav();
if($_GET)
{
    if(isset($_GET['request']) && !empty($_GET['request']))
    {
        // Handle request view
    }
    else if(isset($_GET['request']))    
    {
        
    }
}
else
{
    ob_start();
    //* GET request pending manager approval(of this manager's group)
    $stmt = $conn->prepare("
    SELECT 
        r.T3_requestid, 
        r.T3_submittime, 
        r.T3_reason, 
        r.T3_remark, 
        r.T3_purpose, 
        r.T3_type, 
        r.T3_status,
        r.T3_details,
        u.T1_userid,
        u.T1_username
    FROM T3_request r
    INNER JOIN T1_user u ON r.T3T1_userid = u.T1_userid
    WHERE r.T3_status = 'PENDING_MANAGER_APPROVAL' 
        AND u.T1_group = ?
    ");
    $stmt->bind_param("s", $_SESSION['usergroup']);
    $stmt->execute();
    $requests = $stmt->get_result();
    $stmt->close();
    //username,type,purpose,submittime,details
    ?><table class="table table-bordered">
        <tr>
            <th>Pemohon</th>
            <th>Jenis Permohonan</th>
            <th>Pengguna</th>
            <th>Masa Permohonan</th>
            <th>Butiran</th>
            <th>Pengesahan Pengurus</th>
        </tr>
    <?php
    while($r = mysqli_fetch_assoc($requests))
    {
        $details = json_decode($r['T3_details'],true);
        ?>
        <tr>
            <td><?php echo e($r['T1_username']); ?></td>
            <?php
            if($r['T3_type'] == 'loan')
            {
            $modaltitle = 'Pengesahan Permohonan Aset';
            ?>
            <td><?php echo('Peminjaman Aset'); ?></td>
            <td><?php echo(e($r['T3_purpose'])); ?></td>
            <td><?php echo(mytime($r['T3_submittime'])); ?></td>
            <td>
            <?php
            foreach(array_keys($asset_fields) as $asset_type)
            {
                if(isset($details["${asset_type}_count"]))
                {
                    echo e($asset_type . " x " . $details["${asset_type}_count"]."\n");
                }
            }
            ?>
            </td>
            <?php
            }
            else if($r['T3_type'] == 'book')
            {
                $modaltitle = 'Permohonan Makmal Komputer';
                ?>
                <td>Jenis</td>
                <td>Tempahan Makmal Komputer Tidak Tersedia</td>
            <?php
            }
            ?>
            <td>
                <button class="btn btn-info" onclick="togglemodal('<?php echo('dialog_'.$r['T3_requestid']);?>')">Semak</button>
                <dialog id="dialog_<?php echo($r['T3_requestid']);?>" class="border-none">
                    <button class="btn btn-light float-right" onclick="togglemodal('<?php echo('dialog_'.$r['T3_requestid']);?>')">X</button>
                    <h1><?php echo($modaltitle);?></h1>
                    <section>
                        Pemohon: <?php echo($r['T1_username']);?>
                    </section>
                    <form action="manager.php" method="post">
                        <input type="hidden" name="managerapprove_managerid" value="<?php echo($_SESSION['userid']);?>">
                        <input type="hidden" name="managerapprove_requestid" value="<?php echo($r['T3_requestid']);?>">
                        <label for="managerapprove_comment">Komen</label>
                        <input class="form-control" type="text" name="managerapprove_comment" id="managerapprove_comment">
                        <div class="row m-auto">
                            <input class="btn btn-success m-2" type="submit" name="managerapprove_true" value="Sahkan">
                            <input class="btn btn-danger m-2" type="submit" name="managerapprove_false" value="Tolak">
                        </div>
                    </form>
                </dialog>
                <!--could use dialog if chromium based browser-->
            </td>
        </tr>
        <?php
    }
    ?>
    </table>
    <?php
    $content.= ob_get_clean();
}
?>
<?php include('layout.php');?>