<?php
// session_start();
// include '../koneksi.php'; // Pastikan koneksi ke database sudah benar

// $error = '';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = trim($_POST['username']);
//     $password = trim($_POST['password']);

//     // Cek apakah input tidak kosong
//     if (!empty($username) && !empty($password)) {
//         $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("ss", $username, $username);
//         $stmt->execute();
//         $result = $stmt->get_result();
//         $user = $result->fetch_assoc();

//         if ($user && password_verify($password, $user['password'])) {
//             // Simpan data user ke session
//             $_SESSION['user_id'] = $user['id'];
//             $_SESSION['username'] = $user['username'];
//             $_SESSION['role'] = $user['role'];

//             // Redirect ke halaman utama setelah login
//             header("Location: dashboard.php");
//             exit();
//         } else {
//             $error = "Username atau password salah!";
//         }
//     } else {
//         $error = "Harap isi username/email dan password!";
//     }
// }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="width: 350px;">
        <h3 class="text-center mb-3">Login</h3>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username atau Email</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username atau email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <a href="#">Belum punya akun?</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
