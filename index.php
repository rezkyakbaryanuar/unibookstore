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

// Ambil kata kunci pencarian dari form
$search = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// Query data buku dengan filter pencarian nama buku
if ($search) {
    $sql = "SELECT buku.*, penerbit.nama AS nama_penerbit 
            FROM buku 
            LEFT JOIN penerbit ON buku.id_penerbit = penerbit.id_penerbit 
            WHERE buku.nama_buku LIKE '%$search%' 
            ORDER BY buku.id_buku ASC";
} else {
    $sql = "SELECT buku.*, penerbit.nama AS nama_penerbit 
            FROM buku 
            LEFT JOIN penerbit ON buku.id_penerbit = penerbit.id_penerbit 
            ORDER BY buku.id_buku ASC";
}

$data_buku = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>UNIBOOKSTORE - Home</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav a { margin-right: 15px; font-weight: bold; text-decoration: none; color: #007BFF; }
        nav a:hover { text-decoration: underline; }
        h1 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin-bottom: 20px; }
        input[type="text"] { padding: 6px; width: 250px; }
        input[type="submit"] { padding: 6px 12px; }
    </style>
</head>
<body>

<nav>
    <a href="index.php">HOME</a>
    <a href="admin.php">ADMIN</a>
    <a href="pengadaan.php">PENGADAAN</a>
</nav>
<hr>

<h1>Daftar Buku UNIBOOKSTORE</h1>

<form method="GET" action="index.php">
    <input type="text" name="search" placeholder="Cari nama buku..." value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Cari">
    <?php if ($search): ?>
        <a href="index.php" style="margin-left:10px;">Reset</a>
    <?php endif; ?>
</form>

<table>
    <thead>
        <tr>
            <th>ID Buku</th>
            <th>Kategori</th>
            <th>Nama Buku</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Penerbit</th>
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
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">Data buku tidak ditemukan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
