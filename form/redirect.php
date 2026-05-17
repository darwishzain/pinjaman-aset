<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Original Page</title>
</head>
<body>
    <h1>Redirect as User</h1>
    <form action="lend.php" method="POST">
        <!-- The user never sees this input, but it carries the data -->
        <input type="hidden" name="userid_user" value="12345">
        <input type="hidden" name="usertype_user" value="user">
        <button type="submit">Peminjaman Alatan Komputer</button>
    </form>
    <h1>Redirect as Handler</h1>
    <form action="asset.php" method="POST">
        <!-- The user never sees this input, but it carries the data -->
        <input type="hidden" name="userid_handler" value="12345">
        <input type="hidden" name="usertype_handler" value="handler">
        <button type="submit">Pengurusan Peminjaman Alatan Komputer</button>
    </form>
</body>
</html>