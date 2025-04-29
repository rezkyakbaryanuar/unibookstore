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

// Proses tambah data buku
if (isset($_POST['submit'])) {
    $id_buku = $conn->real_escape_string($_POST['id_buku']);
    $kategori = $conn->real_escape_string($_POST['kategori']);
    $nama_buku = $conn->real_escape_string($_POST['nama_buku']);
    $harga = (float)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $id_penerbit = $conn->real_escape_string($_POST['id_penerbit']);

    // Cek apakah id_buku sudah ada
    $cek = $conn->query("SELECT * FROM buku WHERE id_buku='$id_buku'");
    if ($cek->num_rows > 0) {
        $error = "ID Buku sudah ada!";
    } else {
        $sql = "INSERT INTO buku (id_buku, kategori, nama_buku, harga, stok, id_penerbit) 
                VALUES ('$id_buku', '$kategori', '$nama_buku', $harga, $stok, '$id_penerbit')";
        if ($conn->query($sql)) {
            header("Location: admin.php");
            exit;
        } else {
            $error = "Gagal menambah data: " . $conn->error;
        }
    }
}

// Ambil data penerbit untuk dropdown
$penerbit_result = $conn->query("SELECT id_penerbit, nama FROM penerbit ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku - UNIBOOKSTORE</title>
</head>
<body>
    <h2>Tambah Buku Baru</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="tambah_buku.php">
        <label>ID Buku:<br>
            <input type="text" name="id_buku" required>
        </label><br><br>

        <label>Kategori:<br>
            <input type="text" name="kategori" required>
        </label><br><br>

        <label>Nama Buku:<br>
            <input type="text" name="nama_buku" required>
        </label><br><br>

        <label>Harga:<br>
            <input type="number" name="harga" step="0.01" min="0" required>
        </label><br><br>

        <label>Stok:<br>
            <input type="number" name="stok" min="0" required>
        </label><br><br>

        <label>Penerbit:<br>
            <select name="id_penerbit" required>
                <option value="">-- Pilih Penerbit --</option>
                <?php while($row = $penerbit_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id_penerbit']); ?>">
                        <?php echo htmlspecialchars($row['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label><br><br>

        <input type="submit" name="submit" value="Tambah Buku">
    </form>

    <p><a href="admin.php">Kembali ke Admin</a></p>
</body>
</html>

<?php
$conn->close();
?>
