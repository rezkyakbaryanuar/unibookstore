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

// Ambil data buku beserta nama penerbit
$data_buku = $conn->query("SELECT buku.*, penerbit.nama AS nama_penerbit FROM buku LEFT JOIN penerbit ON buku.id_penerbit = penerbit.id_penerbit ORDER BY buku.id_buku ASC");

// Ambil data penerbit
$data_penerbit = $conn->query("SELECT * FROM penerbit ORDER BY id_penerbit ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - UNIBOOKSTORE</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav a { margin-right: 15px; font-weight: bold; text-decoration: none; color: #007BFF; }
        nav a:hover { text-decoration: underline; }
        h2 { margin-top: 40px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a.button { padding: 6px 12px; background-color: #28a745; color: white; border-radius: 4px; text-decoration: none; }
        a.button:hover { background-color: #218838; }
        .action-links a { margin-right: 10px; color: #007BFF; text-decoration: none; }
        .action-links a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<nav>
    <a href="index.php">HOME</a>
    <a href="admin.php">ADMIN</a>
    <a href="pengadaan.php">PENGADAAN</a>
</nav>
<hr>    

<h1>Halaman Admin - Pengelolaan Buku dan Penerbit</h1>

<!-- Bagian Buku -->
<h2>Daftar Buku</h2>
<a href="tambah_buku.php" class="button">+ Tambah Buku</a>
<table>
    <thead>
        <tr>
            <th>ID Buku</th>
            <th>Kategori</th>
            <th>Nama Buku</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Penerbit</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($data_buku->num_rows > 0): ?>
            <?php while($row = $data_buku->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_buku']); ?></td>
                    <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_buku']); ?></td>
                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo (int)$row['stok']; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_penerbit']); ?></td>
                    <td class="action-links">
                        <a href="edit_buku.php?id=<?php echo urlencode($row['id_buku']); ?>">Edit</a>
                        <a href="hapus_buku.php?id=<?php echo urlencode($row['id_buku']); ?>" onclick="return confirm('Yakin ingin hapus buku ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" style="text-align:center;">Data buku kosong.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Bagian Penerbit -->
<h2>Daftar Penerbit</h2>
<a href="tambah_penerbit.php" class="button">+ Tambah Penerbit</a>
<table>
    <thead>
        <tr>
            <th>ID Penerbit</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Kota</th>
            <th>Telepon</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($data_penerbit->num_rows > 0): ?>
            <?php while($row = $data_penerbit->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_penerbit']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    <td><?php echo htmlspecialchars($row['kota']); ?></td>
                    <td><?php echo htmlspecialchars($row['telepon']); ?></td>
                    <td class="action-links">
                        <a href="edit_penerbit.php?id=<?php echo urlencode($row['id_penerbit']); ?>">Edit</a>
                        <a href="hapus_penerbit.php?id=<?php echo urlencode($row['id_penerbit']); ?>" onclick="return confirm('Yakin ingin hapus penerbit ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">Data penerbit kosong.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
