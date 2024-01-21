<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sewa Nyaman</title>
    <link rel="stylesheet" href="{{ asset('css/riwayat.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Sewa Nyaman Logo">
            </div>
            <nav class="navigation">
                <a href="{{ route('dashboard') }}" class="nav-item ">Pengajuan</a>
                <a href="#" class="nav-item active">Riwayat</a>
            </nav>
        </aside>
        <main class="main-content">
            <header class="header">
                <div class="user-info">
                    <span>Riwayat Pengajuan</span>
                </div>
                <div class="search-container">
                    <input type="text" placeholder="Cari">
                    <button>Search</button>
                </div>
            </header>
            <section class="content">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kontrakan</th>
                            <th>Alamat</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <!-- Tambahkan kolom Status -->
                        </tr>
                    </thead>
                    <tbody>
                                  @foreach ($riwayatPropertis as $index => $riwayatProperti)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $riwayatProperti->nama }}</td>
                    <td>{{ $riwayatProperti->alamat }}</td>
                    <td>{{ $riwayatProperti->harga }}</td>
                    <td>{{ $riwayatProperti->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
