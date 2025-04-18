<?php

include('db.php');

session_start();

// Check if user is logged in, if not redirect to login page
if (isset($_POST['logout'])) {
    session_start();
    session_destroy(); // Hapus semua data sesi
    header("Location: login.php"); // Arahkan ke halaman login
    exit;
}


// Insert Data
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

// Delete Data
if (isset($_POST['delete'])) {
    $kodeBarang = $_POST['deleteKodeBarang'];
    // Hapus data dari database
    $sql = "DELETE FROM barang WHERE kodeBarang=$kodeBarang";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Update Data
if (isset($_POST['update'])) {
    if (isset($_POST['update'])) {
        $kodeBarang = $_POST['kodeBarang'];
        $nama = $_POST['Nama'];
        $alamat = $_POST['Alamat'];
    
        // Debugging
        error_log("kodeBarang: $kodeBarang, Nama: $nama, Alamat: $alamat");
    
        $sql = "UPDATE barang SET Nama='$nama', Alamat='$alamat' WHERE kodeBarang=$kodeBarang";
    
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Data updated successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}

if (isset($_POST['logout'])) {
    session_start();
    session_destroy(); // Hapus semua sesi
    header("Location: login.php"); // Arahkan ke halaman login
    exit;
}

$sql = "SELECT * FROM barang";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .form-container {
            margin: 20px 0;
        }
        .form-container input, .form-container button {
            padding: 10px;
            margin: 5px;
        }
        .popup-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .popup-form.active {
            display: block;
        }

        .inputText {
            margin-bottom: 10px;
            width: 92%;
        }

        .buttonEdit {
            text-align: center;
        }

    </style>
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
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="margin: 0;">Data Barang</h1>
        <form method="POST" action="">
            <button type="submit" name="logout" style="padding: 10px 20px; background-color: #FF0000; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Logout</button>
        </form>
    </div>

    <!-- Insert Form -->
    <h2>Insert Data</h2>
    <div class="form-container">
        <form method="POST" action="">
            <input type="text" name="kodeBarang" placeholder="Kode Barang" required>
            <input type="text" name="Nama" placeholder="Nama Barang" required>
            <input type="text" name="Alamat" placeholder="Alamat" required>
            <button type="submit" name="insert" style="padding: 10px 20px; background-color: #007BFF; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Insert</button>
        </form>
    </div>

    <!-- Table -->
    <h2>Data Table</h2>
    <table>
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
                        <button type="submit" name="delete" onclick="return confirm('Are you sure?')" style="padding: 5px 10px; background-color: #007BFF; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
                    </form>
                    <button type="button" onclick="showEditForm('<?php echo $row['kodeBarang']; ?>', '<?php echo $row['Nama']; ?>', '<?php echo $row['Alamat']; ?>')" style="padding: 5px 10px; background-color: #007BFF; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Edit</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Edit Form (Popup) -->
    <div id="editForm" class="popup-form">
        <form method="POST" action="">
            <div class="inputText">
                <input type="hidden" id="editKodeBarang" name="kodeBarang">
                <input type="text" id="editNama" name="Nama" placeholder="Nama Barang" required style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <input type="text" id="editAlamat" name="Alamat" placeholder="Alamat" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div class="buttonEdit">
                <button type="submit" name="update" style="padding: 10px 20px; background-color: #007BFF; color: #fff; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">Update</button>
                <button type="button" onclick="closeEditForm()" style="padding: 10px 20px; background-color: #ccc; color: #000; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>