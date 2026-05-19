<?php include('function.php'); ?>
<?php
if($_SESSION['usertype'] != 'manager')
{
    alert("Access Denied. Redirecting...", $returnpage);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{

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
    echo($_SESSION['usergroup']);
    $stmt->execute();
    $requests = $stmt->get_result();
    //username,type,purpose,submittime,details
    ?><table class="table table-bordered">
        <tr>
            <th>Pemohon</th>
            <th>Jenis Permohonan</th>
            <th>Tujuan</th>
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
                ?>
                <td>Jenis</td>
                <td>Tempahan Makal Komputer Tidak Tersedia</td>
            <?php
            }
            ?>
            <td>
                <a class="btn btn-success" href="manager.php?approve=<?php echo e($r['T3_requestid']); ?>">Sahkan</a>
                <a class="btn btn-danger" href="manager.php?reject=<?php echo e($r['T3_requestid']); ?>">Tolak</a>
            </td>
        </tr>
        <?php
    }
    ?>
    </table><?php
    
    $content.= ob_get_clean();
}
?>  
<?php include('layout.php');?>