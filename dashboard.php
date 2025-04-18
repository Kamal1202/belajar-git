<?php
include('db.php'); // Menghubungkan ke database

session_start(); // Memulai session

// Mengecek apakah user melakukan logout
if (isset($_POST['logout'])) {
    session_destroy(); // Menghapus semua session
    header("Location: login.php"); // Arahkan kembali ke halaman login
    exit;
}

// Menyimpan data baru ke database
if (isset($_POST['insert'])) {
    $kodeBarang = $_POST['kodeBarang'];
    $nama = $_POST['Nama'];
    $alamat = $_POST['Alamat'];

    $sql = "INSERT INTO barang (kodeBarang, Nama, Alamat) VALUES ('$kodeBarang', '$nama', '$alamat')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data inserted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Menghapus data berdasarkan kodeBarang
if (isset($_POST['delete'])) {
    $kodeBarang = $_POST['deleteKodeBarang'];
    $sql = "DELETE FROM barang WHERE kodeBarang=$kodeBarang";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Mengupdate data berdasarkan kodeBarang
if (isset($_POST['update'])) {
    $kodeBarang = $_POST['kodeBarang'];
    $nama = $_POST['Nama'];
    $alamat = $_POST['Alamat'];

    $sql = "UPDATE barang SET Nama='$nama', Alamat='$alamat' WHERE kodeBarang=$kodeBarang";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data updated successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Mengambil semua data barang dari database
$sql = "SELECT * FROM barang";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function showEditForm(kodeBarang, nama, alamat) {
            document.getElementById('editForm').classList.add('active');
            document.getElementById('editKodeBarang').value = kodeBarang;
            document.getElementById('editNama').value = nama;
            document.getElementById('editAlamat').value = alamat;
        }
        function closeEditForm() {
            document.getElementById('editForm').classList.remove('active');
        }
    </script>
</head>
<body>
<div class="container">
        <div class="header">
            <h1>Data Barang</h1>
            <form method="POST" action="">
                <button type="submit" name="logout" class="btn btn-red">Logout</button>
            </form>
        </div>

        <h2>Insert Data</h2>
        <div class="form-container">
            <form method="POST" action="">
                <input type="text" name="kodeBarang" placeholder="Kode Barang" required>
                <input type="text" name="Nama" placeholder="Nama Barang" required>
                <input type="text" name="Alamat" placeholder="Alamat" required>
                <button type="submit" name="insert" class="btn btn-blue">Insert</button>
            </form>
        </div>

        <h2>Data Table</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['kodeBarang']; ?></td>
                            <td><?php echo $row['Nama']; ?></td>
                            <td><?php echo $row['Alamat']; ?></td>
                            <td>
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="deleteKodeBarang" value="<?php echo $row['kodeBarang']; ?>">
                                    <button type="submit" name="delete" onclick="return confirm('Are you sure?')" class="btn btn-blue">Delete</button>
                                </form>
                                <button type="button" onclick="showEditForm('<?php echo $row['kodeBarang']; ?>', '<?php echo $row['Nama']; ?>', '<?php echo $row['Alamat']; ?>')" class="btn btn-yellow">Edit</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Edit form (popup) tetap di luar container -->
        <div id="editForm" class="popup-form">
            <form method="POST" action="">
                <div class="inputText">
                    <input type="hidden" id="editKodeBarang" name="kodeBarang">
                    <input type="text" id="editNama" name="Nama" placeholder="Nama Barang" required class="edit-input">
                    <input type="text" id="editAlamat" name="Alamat" placeholder="Alamat" required class="edit-input">
                </div>
                <div class="buttonEdit">
                    <button type="submit" name="update" class="btn btn-blue" style="margin-right: 10px;">Update</button>
                    <button type="button" onclick="closeEditForm()" class="btn btn-grey">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>