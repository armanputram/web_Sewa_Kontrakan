<!-- resources/views/index.blade.php -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Nyaman - Halaman Utama</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

</head>
<body>
    <div class="header">
        <!-- Mungkin tambahkan logo atau judul aplikasi di sini -->
        <h1>Sewa Nyaman</h1>
    </div>

    <div class="content">
        <h2>Selamat datang di Sewa Nyaman</h2>
        <p>
            Sewa Nyaman adalah platform untuk pengelola properti yang memudahkan Anda dalam mengelola dan
            memantau properti Anda secara efisien. Login atau daftar sebagai pengelola untuk memulai!
        </p>

        <div class="cta-buttons">
            <a href="{{ route('login') }}" class="btn">Login</a>
            <a href="{{ route('register') }}" class="btn">Daftar</a>
        </div>
    </div>

    <!-- Tambahkan footer atau bagian lainnya jika diperlukan -->

    <!-- Tambahkan tautan ke file JavaScript eksternal jika diperlukan -->
</body>
</html>
