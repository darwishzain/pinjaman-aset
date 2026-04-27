<?php
include 'config.php';
include 'samplesession.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Permohonan Sewaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <form class="w-75 m-auto" action="" method="post">
        <h1>Borang Permohonan Sewaan Alatan Komputer</h1>
        <input class="form-control" type="text" value="" placeholder="Nama Pemohon">
        <select class="form-control" name="classification" id="">
            <option value="digital">BPH</option>
            <option value="digital">BD</option>
            <option value="digital">PSM</option>
        </select>
        <input class="form-control" type="text" placeholder="Tujuan">
        <input class="form-control" type="datetime-local" name="" id="" value="">
        <input class="form-control" type="datetime-local" name="" id="" value="">
        <input class="form-control" type="text" placeholder="Tempat Penggunaan">
        <textarea class="form-control" name="remark" id="" placeholder="Catatan (Jika Perlu)" ></textarea>
        <h2 class="text-center">Kegunaan</h2>
        <div class="form-check">
            <input type="radio" class="form-check-input" id="radio1" name="usage" value="option1" checked>Individu
            <label class="form-check-label" for="radio1"></label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" id="radio2" name="usage" value="option2">Bahagian/Jabatan
            <label class="form-check-label" for="radio2"></label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="projector" name="projectpr" value="eq-projector"> Projector
            <label class="form-check-label" for="radio2"></label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="projector" name="projectpr" value="eq-pclaptop"> Komputer/Notebook
            <label class="form-check-label" for="radio2"></label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="projector" name="projectpr" value="eq-others">Lain-lain (Nyatakan)
            <label class="form-check-label" for="radio2"></label>
        </div>
    </form>
</body>
</html>