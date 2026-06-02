<?php include('function.php'); ?>
<?php
ob_start();
//TODO: User management(superadmin), view profile (admin), view personal profile(user)
if(isset($_SESSION['userid']))
{
    ?><?php
    echo("<h3>Selamat Datang, ".$_SESSION['username']."</h3>");
    if($_SESSION['userrole'] == 'superadmin')
    {
        $stmt = $conn->prepare("SELECT * FROM T1_user");
        $stmt->execute();
        $userlist = $stmt->get_result();
        ?>
        <table class="table table-bordered">
            <tr>
                <th>Pengguna</th>
                <th>Peranan</th>
            </tr>
        <?php
        while($user = mysqli_fetch_assoc($userlist))
        {
            ?>
            <tr>
                <td><?= escape($user['T1_userid']); ?> - <?= escape($user['T1_username']); ?></td>
                <td><?= escape($user['T1_role']); ?></td>
            </tr>
            <?php
        }
        ?>
        </table>
        <?php
    }
}
else
{
    ?><h3>Anda belum log masuk.</h3><?php
}
$content = ob_get_clean();
?>
<?php include('views/layout-master.php');?>