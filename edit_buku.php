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

// Ambil id_buku dari parameter GET
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id_buku = $conn->real_escape_string($_GET['id']);

// Ambil data buku berdasarkan id_buku
$result = $conn->query("SELECT * FROM buku WHERE id_buku='$id_buku'");
if ($result->num_rows == 0) {
    header("Location: admin.php");
    exit;
}
$buku = $result->fetch_assoc();

// Ambil data penerbit untuk dropdown
$penerbit_result = $conn->query("SELECT id_penerbit, nama FROM penerbit ORDER BY nama ASC");

// Proses update data jika form disubmit
if (isset($_POST['submit'])) {
    $kategori = $conn->real_escape_string($_POST['kategori']);
    $nama_buku = $conn->real_escape_string($_POST['nama_buku']);
    $harga = (float)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $id_penerbit = $conn->real_escape_string($_POST['id_penerbit']);

    $sql = "UPDATE buku SET kategori='$kategori', nama_buku='$nama_buku', harga=$harga, stok=$stok, id_penerbit='$id_penerbit' WHERE id_buku='$id_buku'";
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
    <title>Edit Buku - UNIBOOKSTORE</title>
</head>
<body>
    <h2>Edit Buku</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="edit_buku.php?id=<?php echo urlencode($id_buku); ?>">
        <label>ID Buku:<br>
            <input type="text" value="<?php echo htmlspecialchars($buku['id_buku']); ?>" readonly>
        </label><br><br>

        <label>Kategori:<br>
            <input type="text" name="kategori" value="<?php echo htmlspecialchars($buku['kategori']); ?>" required>
        </label><br><br>

        <label>Nama Buku:<br>
            <input type="text" name="nama_buku" value="<?php echo htmlspecialchars($buku['nama_buku']); ?>" required>
        </label><br><br>

        <label>Harga:<br>
            <input type="number" name="harga" step="0.01" min="0" value="<?php echo htmlspecialchars($buku['harga']); ?>" required>
        </label><br><br>

        <label>Stok:<br>
            <input type="number" name="stok" min="0" value="<?php echo htmlspecialchars($buku['stok']); ?>" required>
        </label><br><br>

        <label>Penerbit:<br>
            <select name="id_penerbit" required>
                <option value="">-- Pilih Penerbit --</option>
                <?php while($row = $penerbit_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id_penerbit']); ?>" <?php if ($row['id_penerbit'] == $buku['id_penerbit']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($row['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label><br><br>

        <input type="submit" name="submit" value="Update Buku">
    </form>

    <p><a href="admin.php">Kembali ke Admin</a></p>
</body>
</html>

<?php
$conn->close();
?>
