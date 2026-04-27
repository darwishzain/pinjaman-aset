<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
#! Load .env
$envpath = '../../.env';
if (!file_exists($envpath)) return;
$lines = file($envpath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (str_starts_with($line, '#')) continue;
    list($key, $value) = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value);
    $_ENV[$key] = $value;
    putenv("$key=$value");
}

$dbhost = $_ENV['DB_HOST'] ?? '';
$dbuser = $_ENV['DB_USER'] ?? '';
$dbpass = $_ENV['DB_PASS'] ?? '';
$dbname = $_ENV['DB_NAME'] ?? '';

$conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
if(!$conn)
{
    echo("<script>console.log('Connection Failed');</script>");
    die("Connection Failed:".mysqli_connect_error());
}
else{
    echo("<script>console.log('Connection Successful');</script>");
}

?>