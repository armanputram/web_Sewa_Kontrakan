<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin Sewa Nyaman</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- Tautan ke file CSS eksternal Anda -->
</head>
<body>
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" width="50%">
    </div>
    <div class="card">
        <h1>Register Admin</h1>
        <!-- Pastikan URL di action mengarah ke rute yang tepat -->
        <form action="{{ route('registerpengelola') }}" method="POST">
            @csrf <!-- CSRF Token -->
            <div class="form-group">
                <label for="nama">Nama</label>
                <!-- Menambahkan old('name') akan mengisi kembali input dengan nilai sebelumnya saat validasi gagal -->
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                <!-- Menampilkan pesan error untuk field name -->
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Alamat email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" name="password" class="form-control">
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Tambahkan field untuk konfirmasi password -->
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <button class="btn" type="submit">Daftar</button>
        </form>
    </div>
</body>
</html>
