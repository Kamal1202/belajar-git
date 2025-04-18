<?php
session_start(); // Memulai session untuk menyimpan data login pengguna

include('db.php'); // Menghubungkan file koneksi database

// Mengecek apakah form sudah dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; // Mengambil data username dari form
    $password = $_POST['password']; // Mengambil data password dari form

    // Query untuk mencocokkan username dan password dengan database
    $query = "SELECT * FROM login WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password); // Menggunakan prepared statement untuk keamanan
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika ditemukan data, berarti login berhasil
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Mengambil data pengguna
        $_SESSION['loggedin'] = true; // Menandai bahwa user sudah login
        $_SESSION['username'] = $user['username']; // Menyimpan username ke session
        header('Location: dashboard.php'); // Redirect ke halaman dashboard
        exit;
    } else {
        $error = 'Invalid username or password!'; // Jika tidak cocok, tampilkan pesan error
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>