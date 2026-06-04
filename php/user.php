<?php include('function.php'); ?>
<?php
ob_start();
//TODO: User management(superadmin), view profile (admin), view personal profile(user)
?>
<dialog id="userdialog"></dialog>
<?php
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
    else if(isset($_GET['adduser']))
    {
        ?>
        <script>
            function adduserform()
            {
                const dialog = document.getElementById('userdialog');
                const formcontent = `
                <button type="button" class="btn float-right" aria-label="Close" onclick="closedialog(document.getElementById('userdialog'))">X</button>
            <form action="user.php" method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="login_id" placeholder="Pengguna" aria-label="User's Name" aria-describedby="basic-addon2">
                    <span class="input-group-text" id="basic-addon2">@pkink.gov.my</span>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="login_password" id="" placeholder="Kata Laluan">
                </div>
                <select class="form-control" name="new_role" id="select_role">
                    <option value="">Memuatkan</option>
                </select>
                <div class="input-group mb-3">
                    <button type="submit" class="btn btn-primary" name="adduser_btn">Tambah Pengguna</button>
                </div>
            </form>
                `;
                const parsedContent = document.createRange().createContextualFragment(formcontent);
                const roleSelect = parsedContent.getElementById('select_role');
                const apiUrl = 'http://localhost/item-rental/api/role/get.php';//##replace

                // Fetch data from your API
                fetch(apiUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Clear the loading message
                        roleSelect.innerHTML = '<option value="">-- Pilih Peranan --</option>';
                        // Loop through the JSON data and build options
                        data.data.forEach(role => {
                            const option = document.createElement('option');
                            option.value = role.T2_id; // Sets the option value
                            option.textContent = role.T2_name; // Sets the visible text
                            roleSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching roles:', error);
                        roleSelect.innerHTML = '<option value="">Failed to load roles</option>';
                    });
                opendialog(dialog,parsedContent);
            }
        </script>
        <?php
    }
    ?><?php
    echo("<h3>Selamat Datang, ".$_SESSION['usertype'].$_SESSION['userrole']."</h3>");
    if($_SESSION['userrole'] == 'superadmin')
    {
        $stmt = $conn->prepare("SELECT * FROM T1_user");
        $stmt->execute();
        $userlist = $stmt->get_result();
        ?>
        <button class="btn btn-primary" onclick="adduserform()">Tambah Pengguna</button>
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