<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo($title??"Bahagian Digital");?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <div id="container" class="text-center">
        <?php
        if(isset($_SESSION['username']))
        {
            ?><a class="btn btn-danger" href="?logout">Log Keluar</a><?php
        }
        else
        {
            ?><button type="button" class="btn btn-primary" onclick="document.getElementById('logindialog').showModal()">Log Masuk</button>
                <dialog id="logindialog">
                    <button type="button" class="btn float-right" onclick="document.getElementById('logindialog').close()">x</button>
                    <h2>Log Masuk</h2>
                    <form method="post" action="user.php">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="login_id" placeholder="Pengguna" aria-label="User's Name" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">@pkink.gov.my</span>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="login_password" id="" placeholder="Kata Laluan">
                        </div>
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-primary" name="login_true">Log Masuk</button>
                        </div>
                    </form>
                </dialog>
                <script>document.getElementById('logindialog').showModal();</script>
            <?php
        }
        ?>
        <!-- <h1><?php //echo($title??"Bahagian Digital");?></h1> -->
        <?php if(!empty($content)){echo($content);}?>
    </div>
</body>
</html>