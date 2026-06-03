<?php include('function.php'); ?>
<?php
ob_start();
//TODO: User management(superadmin), view profile (admin), view personal profile(user)
if(isset($_SESSION['userid']))
{
    if(isset($_GET['role']))
    {
        ?>
        <table>
            <tr>
                <th>Pengguna</th>
                <th>Peranan</th>
            </tr>
            <tr>
                <td><?= escape($_SESSION['username']); ?></td>
                <td><?= escape($_SESSION['userrole']); ?></td>
            </tr>

        </table><?php
    }
    ?><?php
    echo("<h3>Selamat Datang, ".$_SESSION['userrole']."</h3>");
    if($_SESSION['userrole'] == 'superadmin')
    {
        $stmt = $conn->prepare("SELECT * FROM T1_user");
        $stmt->execute();
        $userlist = $stmt->get_result();
        ?>
        <dialog id="userprofile"></dialog>
        <table class="table table-bordered">
            <tr>
                <th>Pengguna</th>
                <th>Peranan</th>
                <th>Tindakan</th>
            </tr>
        <?php
        while($user = mysqli_fetch_assoc($userlist))
        {
            ?>
            <tr>
                <td><?= escape($user['T1_username']); ?></td>
                <td><?= escape($user['T1_role']); ?></td>
                <td>
                    <form action="user.php" method="post">
                        <input type="text" name="role_userid" id="" value="<?= escape($user['T1_userid']); ?>" hidden>
                        
                    </form>
                    <button class="btn btn-sm btn-primary" onclick="viewProfile('<?= escape($user['T1_username']); ?>')">View Profile</button>
                </td>
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