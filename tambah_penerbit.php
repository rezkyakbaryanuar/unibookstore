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

$error = "";

// Proses tambah data penerbit
if (isset($_POST['submit'])) {
    $id_penerbit = $conn->real_escape_string($_POST['id_penerbit']);
    $nama = $conn->real_escape_string($_POST['nama']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $kota = $conn->real_escape_string($_POST['kota']);
    $telepon = $conn->real_escape_string($_POST['telepon']);

    // Cek apakah id_penerbit sudah ada
    $cek = $conn->query("SELECT * FROM penerbit WHERE id_penerbit='$id_penerbit'");
    if ($cek->num_rows > 0) {
        $error = "ID Penerbit sudah ada!";
    } else {
        $sql = "INSERT INTO penerbit (id_penerbit, nama, alamat, kota, telepon) 
                VALUES ('$id_penerbit', '$nama', '$alamat', '$kota', '$telepon')";
        if ($conn->query($sql)) {
            header("Location: admin.php");
            exit;
        } else {
            $error = "Gagal menambah data: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Penerbit - UNIBOOKSTORE</title>
</head>
<body>
    <h2>Tambah Penerbit Baru</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="tambah_penerbit.php">
        <label>ID Penerbit:<br>
            <input type="text" name="id_penerbit" required>
        </label><br><br>

        <label>Nama:<br>
            <input type="text" name="nama" required>
        </label><br><br>

        <label>Alamat:<br>
            <input type="text" name="alamat">
        </label><br><br>

        <label>Kota:<br>
            <input type="text" name="kota">
        </label><br><br>

        <label>Telepon:<br>
            <input type="text" name="telepon">
        </label><br><br>

        <input type="submit" name="submit" value="Tambah Penerbit">
    </form>

    <p><a href="admin.php">Kembali ke Admin</a></p>
</body>
</html>

<?php
$conn->close();
?>
