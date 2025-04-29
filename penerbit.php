<?php
// Koneksi ke database MySQL
$host = "localhost:8111";
$user = "root";       // username default XAMPP biasanya 'root'
$password = "";       // password default biasanya kosong
$database = "unibook";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Inisialisasi variabel
$id_penerbit = "";
$nama = "";
$alamat = "";
$kota = "";
$telepon = "";
$edit_mode = false;
$error = "";

// Proses tambah data
if (isset($_POST['tambah'])) {
    $id_penerbit = $conn->real_escape_string($_POST['id_penerbit']);
    $nama = $conn->real_escape_string($_POST['nama']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $kota = $conn->real_escape_string($_POST['kota']);
    $telepon = $conn->real_escape_string($_POST['telepon']);

    // Cek duplikat ID
    $cek = $conn->query("SELECT * FROM penerbit WHERE id_penerbit='$id_penerbit'");
    if ($cek->num_rows > 0) {
        $error = "ID Penerbit sudah ada!";
    } else {
        $sql = "INSERT INTO penerbit (id_penerbit, nama, alamat, kota, telepon) VALUES ('$id_penerbit', '$nama', '$alamat', '$kota', '$telepon')";
        if ($conn->query($sql)) {
            header("Location: penerbit.php");
            exit;
        } else {
            $error = "Gagal menambah data: " . $conn->error;
        }
    }
}

// Proses edit (tampilkan data di form)
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $conn->real_escape_string($_GET['edit']);
    $res = $conn->query("SELECT * FROM penerbit WHERE id_penerbit='$id'");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $id_penerbit = $row['id_penerbit'];
        $nama = $row['nama'];
        $alamat = $row['alamat'];
        $kota = $row['kota'];
        $telepon = $row['telepon'];
    } else {
        header("Location: penerbit.php");
        exit;
    }
}

// Proses update data
if (isset($_POST['update'])) {
    $id_penerbit = $conn->real_escape_string($_POST['id_penerbit']);
    $nama = $conn->real_escape_string($_POST['nama']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $kota = $conn->real_escape_string($_POST['kota']);
    $telepon = $conn->real_escape_string($_POST['telepon']);

    $sql = "UPDATE penerbit SET nama='$nama', alamat='$alamat', kota='$kota', telepon='$telepon' WHERE id_penerbit='$id_penerbit'";
    if ($conn->query($sql)) {
        header("Location: penerbit.php");
        exit;
    } else {
        $error = "Gagal update data: " . $conn->error;
    }
}

// Proses hapus data
if (isset($_GET['hapus'])) {
    $id = $conn->real_escape_string($_GET['hapus']);
    $conn->query("DELETE FROM penerbit WHERE id_penerbit='$id'");
    header("Location: penerbit.php");
    exit;
}

// Ambil data penerbit
$data = $conn->query("SELECT * FROM penerbit ORDER BY id_penerbit ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengelolaan Penerbit - UNIBOOKSTORE</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin-top: 20px; }
        input[type=text] { padding: 6px; width: 300px; margin-bottom: 10px; }
        input[type=submit] { padding: 6px 12px; }
        .error { color: red; margin-bottom: 10px; }
        a { text-decoration: none; color: blue; }
        a:hover { text-decoration: underline; }
        nav a { margin-right: 15px; }
    </style>
</head>
<body>

<h2>Pengelolaan Penerbit - UNIBOOKSTORE</h2>

<nav>
    <a href="index.php">HOME</a>
    <a href="admin.php">ADMIN</a>
    <a href="pengadaan.php">PENGADAAN</a>
</nav>

<hr>

<?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" action="penerbit.php">
    <label>ID Penerbit:<br>
        <?php if ($edit_mode): ?>
            <input type="text" name="id_penerbit" value="<?php echo htmlspecialchars($id_penerbit); ?>" readonly>
        <?php else: ?>
            <input type="text" name="id_penerbit" value="<?php echo htmlspecialchars($id_penerbit); ?>" required>
        <?php endif; ?>
    </label><br>

    <label>Nama:<br>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
    </label><br>

    <label>Alamat:<br>
        <input type="text" name="alamat" value="<?php echo htmlspecialchars($alamat); ?>">
    </label><br>

    <label>Kota:<br>
        <input type="text" name="kota" value="<?php echo htmlspecialchars($kota); ?>">
    </label><br>

    <label>Telepon:<br>
        <input type="text" name="telepon" value="<?php echo htmlspecialchars($telepon); ?>">
    </label><br>

    <?php if ($edit_mode): ?>
        <input type="submit" name="update" value="Update Penerbit">
        <a href="penerbit.php">Batal</a>
    <?php else: ?>
        <input type="submit" name="tambah" value="Tambah Penerbit">
    <?php endif; ?>
</form>

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
        <?php if ($data->num_rows > 0): ?>
            <?php while($row = $data->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_penerbit']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    <td><?php echo htmlspecialchars($row['kota']); ?></td>
                    <td><?php echo htmlspecialchars($row['telepon']); ?></td>
                    <td>
                        <a href="penerbit.php?edit=<?php echo urlencode($row['id_penerbit']); ?>">Edit</a> |
                        <a href="penerbit.php?hapus=<?php echo urlencode($row['id_penerbit']); ?>" onclick="return confirm('Yakin ingin hapus data ini?');">Hapus</a>
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
