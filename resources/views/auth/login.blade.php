<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Sewa Nyaman</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> <!-- Tautan ke file CSS eksternal Anda -->
</head>
<body>
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" width="50%">
    </div>
    <div class="card">
        <h1>Login Admin</h1>
        <!-- Pastikan action diarahkan ke rute login yang telah Anda tentukan di web.php -->
        <form action="{{ route('loginPengelola') }}" method="POST">
            @csrf <!-- CSRF Token -->
            <div class="form-group">
                <label for="email">Alamat email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                <!-- Tampilkan pesan error untuk email -->
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" name="password" class="form-control">
                <!-- Tampilkan pesan error untuk password -->
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <button class="btn" type="submit">Login</button>
            
        </form>
    </div>
</body>
</html>
