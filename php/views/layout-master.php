<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo($title??"Bahagian Digital");?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <dialog id="dialog"></dialog>
    <div id="container" class="text-center">
        <?php
        if(isset($_SESSION['username']))
        {
            ?><a class="btn btn-danger" href="?logout">Log Keluar</a><?php
        }
        else
        {
            
        }
        ?>
        <!-- <h1><?php //echo($title??"Bahagian Digital");?></h1> -->
        <?php if(!empty($content)){echo($content);}?>
    </div>
</body>
</html>