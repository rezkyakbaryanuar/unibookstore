<?php
// Koneksi database
$host = "localhost:8111";
$user = "root";       // username default XAMPP biasanya 'root'
$password = "";       // password default biasanya kosong
$database = "unibook";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah parameter id_penerbit ada
if (isset($_GET['id'])) {
    $id_penerbit = $conn->real_escape_string($_GET['id']);
    $conn->query("DELETE FROM penerbit WHERE id_penerbit='$id_penerbit'");
}

$conn->close();
header("Location: admin.php");
exit;
?>
