<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sewa Nyaman</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
                <a href="#" class="nav-item active">Pengajuan</a>
                <a href="{{ route('riwayat') }}" class="nav-item">Riwayat</a>
            </nav>
        </aside>
        <main class="main-content">
            <header class="header">
                <div class="user-info">
                    <span>Admin</span>
                </div>
                <div class="search-container">
                    <input type="text" placeholder="Cari">
                    <button>Search</button>
                </div>
                 
                
                <div class="logout-container">
        <form action="{{ route('logout') }}" method="POST">
            @csrf <!-- CSRF Token -->
            <button type="submit">Logout</button>
        </form>
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
                        @foreach ($propertis as $index => $properti)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $properti->nama }}</td>
                            <td>{{ $properti->alamat }}</td>
                            <td>{{ $properti->harga }}</td>
                            <td>
                                <span class="status" id="status-{{ $index + 1 }}">{{ $properti->status }}</span>
                                <!-- Tampilkan status dari data -->
                            </td>
                            <td>
                                <a href="#" class="navbar-setuju" id="setuju-{{ $properti->id_properti }}"
                                    onclick="updateStatus('{{ $properti->id_properti }}', 'setuju')">Setuju</a>
                                <a href="#" class="navbar-tolak" id="tolak-{{ $properti->id_properti }}"
                                    onclick="updateStatus('{{ $properti->id_properti }}', 'tolak')">Tolak</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            <!-- Tambahkan script JavaScript -->
            @foreach ($propertis as $index => $properti)
            <script>
            function hideButtons(id) {
                $('#setuju-' + id).hide();
                $('#tolak-' + id).hide();
                // Simpan status tombol pada local storage
                localStorage.setItem('status_setuju_' + id, 'hidden');
                localStorage.setItem('status_tolak_' + id, 'hidden');
            }

            function showButtons(id) {
                $('#setuju-' + id).show();
                $('#tolak-' + id).show();
                // Simpan status tombol pada local storage
                localStorage.setItem('status_setuju_' + id, 'visible');
                localStorage.setItem('status_tolak_' + id, 'visible');
            }

            $(document).ready(function() {
                var statusSetuju = localStorage.getItem('status_setuju_{{ $properti->id_properti }}');
                var statusTolak = localStorage.getItem('status_tolak_{{ $properti->id_properti }}');
                var statusProperti = '{{ $properti->status }}';

                if (statusSetuju === 'hidden' && statusTolak === 'hidden' && statusProperti === 'menunggu') {
                    showButtons('{{ $properti->id_properti }}');
                } else {
                    hideButtons('{{ $properti->id_properti }}');
                }
            });

            function updateStatus(id, status) {
                $.ajax({
                    url: '/update-status/' + id,
                    type: 'POST',
                    data: {
                        status: status
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Update tampilan status di tabel
                        $('#status-' + id).text(response.data.status);

                        // Sembunyikan kedua tombol setelah salah satu ditekan
                        hideButtons(id);
                    },
                    error: function(error) {
                        console.error('Error updating status:', error);
                    }
                });
            }
            </script>
            @endforeach
        </main>
    </div>
</body>

</html>