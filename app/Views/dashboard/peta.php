<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        #peta { height: 500px; border-radius: 8px; }
        nav a { margin-right: 12px; }
    </style>
</head>
<body>
    <nav>
        <a href="/dashboard">Dashboard</a>
        <a href="/peta">Peta Sebaran</a>
        <a href="/notifikasi">Notifikasi</a>
    </nav>

    <h1><?= esc($title) ?></h1>
    <div id="peta"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const lokasi = <?= json_encode($lokasi) ?>;

        const peta = L.map('peta').setView([-3.9985, 122.5129], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(peta);

        const titik = [];
        lokasi.forEach(function (l) {
            const lat = parseFloat(l.latitude);
            const lng = parseFloat(l.longitude);
            titik.push([lat, lng]);

            const isi = document.createElement('div');
            const judul = document.createElement('b');
            judul.textContent = l.judul;
            isi.appendChild(judul);
            isi.appendChild(document.createElement('br'));
            isi.appendChild(document.createTextNode((l.nama_lokasi || '-') + ' | ' + l.status));

            L.marker([lat, lng]).addTo(peta).bindPopup(isi);
        });

        if (titik.length > 0) {
            peta.fitBounds(titik, { padding: [40, 40] });
        }
    </script>
</body>
</html>