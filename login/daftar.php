<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="width: 350px;">
        <h3 class="text-center">Daftar</h3>
        <hr>
        <form>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="username" class="form-control" id="username" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Masukkan email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php">Sudah punya akun?</a>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>