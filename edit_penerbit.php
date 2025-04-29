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

// Ambil id_penerbit dari parameter GET
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id_penerbit = $conn->real_escape_string($_GET['id']);

// Ambil data penerbit berdasarkan id_penerbit
$result = $conn->query("SELECT * FROM penerbit WHERE id_penerbit='$id_penerbit'");
if ($result->num_rows == 0) {
    header("Location: admin.php");
    exit;
}
$penerbit = $result->fetch_assoc();

// Proses update data jika form disubmit
if (isset($_POST['submit'])) {
    $nama = $conn->real_escape_string($_POST['nama']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $kota = $conn->real_escape_string($_POST['kota']);
    $telepon = $conn->real_escape_string($_POST['telepon']);

    $sql = "UPDATE penerbit SET nama='$nama', alamat='$alamat', kota='$kota', telepon='$telepon' WHERE id_penerbit='$id_penerbit'";
    if ($conn->query($sql)) {
        header("Location: admin.php");
        exit;
    } else {
        $error = "Gagal update data: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Penerbit - UNIBOOKSTORE</title>
</head>
<body>
    <h2>Edit Penerbit</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="edit_penerbit.php?id=<?php echo urlencode($id_penerbit); ?>">
        <label>ID Penerbit:<br>
            <input type="text" value="<?php echo htmlspecialchars($penerbit['id_penerbit']); ?>" readonly>
        </label><br><br>

        <label>Nama:<br>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($penerbit['nama']); ?>" required>
        </label><br><br>

        <label>Alamat:<br>
            <input type="text" name="alamat" value="<?php echo htmlspecialchars($penerbit['alamat']); ?>">
        </label><br><br>

        <label>Kota:<br>
            <input type="text" name="kota" value="<?php echo htmlspecialchars($penerbit['kota']); ?>">
        </label><br><br>

        <label>Telepon:<br>
            <input type="text" name="telepon" value="<?php echo htmlspecialchars($penerbit['telepon']); ?>">
        </label><br><br>

        <input type="submit" name="submit" value="Update Penerbit">
    </form>

    <p><a href="admin.php">Kembali ke Admin</a></p>
</body>
</html>

<?php
$conn->close();
?>
