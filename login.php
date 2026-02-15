<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $cek = mysqli_query($koneksi, "
        SELECT * FROM users 
        WHERE email='$email' 
        AND password='$password'
    ");

    if (mysqli_num_rows($cek) > 0) {

        $data = mysqli_fetch_assoc($cek);

        $_SESSION['id_user'] = $data['id'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        echo "<script>
                alert('Login berhasil');
                location='index.php';
              </script>";

    } else {

        echo "<script>
                alert('Email atau Password salah');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>fadlicode - Toko Bangunan</title>
    <link rel="icon" href="assets/images/topicon.png">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
        }

        .login-card .card-body {
            padding: 30px;
        }
    </style>
</head>

<body>

    <div class="card shadow-lg login-card">
        <div class="card-body">

            <h4 class="text-center mb-4">Login</h4>

            <form method="POST">

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100">
                    Login
                </button>

            </form>

        </div>
    </div>

</body>

</html>