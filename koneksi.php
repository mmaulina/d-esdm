<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "d-esdm";

$conn =  new mysqli($host, $username, $password, $database);

if ($conn ->connect_error) {
    die("Koneksi gagal: " . $koneksi ->connect_error);
}
?>
