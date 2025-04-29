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
$success = "";

// Proses tambah stok pengadaan
if (isset($_POST['submit'])) {
    $id_buku = $conn->real_escape_string($_POST['id_buku']);
    $jumlah = (int)$_POST['jumlah'];
    $tanggal = date('Y-m-d');

    if ($jumlah <= 0) {
        $error = "Jumlah stok harus lebih dari 0.";
    } else {
        // Update stok buku
        $conn->query("UPDATE buku SET stok = stok + $jumlah WHERE id_buku = '$id_buku'");

        // Simpan data pengadaan (jika tabel pengadaan ada)
        $conn->query("INSERT INTO pengadaan (id_buku, jumlah, tanggal) VALUES ('$id_buku', $jumlah, '$tanggal')");

        $success = "Stok berhasil ditambahkan.";
    }
}

// Ambil data buku untuk dropdown
$result = $conn->query("SELECT id_buku, nama_buku FROM buku ORDER BY nama_buku ASC");

// Ambil data buku dengan stok di bawah 10
$stok_rendah = $conn->query("SELECT id_buku, nama_buku, stok FROM buku WHERE stok < 10 ORDER BY stok ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengadaan Buku - UNIBOOKSTORE</title>
    <style>
        table { border-collapse: collapse; width: 50%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Pengadaan Buku (Tambah Stok)</h2>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="pengadaan.php">
        <label>Pilih Buku:<br>
            <select name="id_buku" required>
                <option value="">-- Pilih Buku --</option>
                <?php while($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id_buku']); ?>">
                        <?php echo htmlspecialchars($row['nama_buku']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label><br><br>

        <label>Jumlah Stok Tambahan:<br>
            <input type="number" name="jumlah" min="1" required>
        </label><br><br>

        <input type="submit" name="submit" value="Tambah Stok">
    </form>

    <h3>Buku dengan Stok di Bawah 10</h3>
    <?php if ($stok_rendah->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Buku</th>
                    <th>Nama Buku</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $stok_rendah->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id_buku']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_buku']); ?></td>
                        <td><?php echo (int)$row['stok']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Semua buku memiliki stok cukup.</p>
    <?php endif; ?>

    <p><a href="admin.php">Kembali ke Admin</a></p>
</body>
</html>

<?php
$conn->close();
?>
