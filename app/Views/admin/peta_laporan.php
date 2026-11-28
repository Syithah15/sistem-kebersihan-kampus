<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<!-- CDN Leaflet -->
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>
    :root {
        --eco-green: #15803D;
        --eco-dark-green: #14532D;
        --eco-light-green: #F2F9F5;
    }

    .bg-eco-primary {
        background-color: var(--eco-green) !important;
        color: #ffffff !important;
    }

    .bg-eco-soft {
        background-color: var(--eco-light-green) !important;
    }

    .text-eco-primary {
        color: var(--eco-green) !important;
    }

    .btn-touch {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-eco-primary {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
        color: #ffffff;
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn-eco-primary:hover {
        background-color: var(--eco-dark-green);
        border-color: var(--eco-dark-green);
        color: #ffffff;
    }

    #petaLaporan {
        width: 100%;
        height: 560px;
        border-radius: 12px;
        z-index: 1;
    }

    .info-peta {
        font-size: 14px;
        line-height: 1.6;
    }

    .panel-lokasi-wrap {
        height: 560px;
        display: flex;
        flex-direction: column;
    }

    .panel-lokasi-body {
        overflow-y: auto;
        flex: 1 1 auto;
    }

    .lokasi-report-card {
        border-left: 4px solid var(--eco-green);
        border-radius: 8px;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .lokasi-report-card:hover {
        background: var(--eco-light-green);
        transform: translateX(2px);
    }

    .marker-cluster-custom {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        font-weight: bold;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid py-2">

    <!-- JUDUL & NAVIGASI -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-eco-primary px-3 py-1">
                    <i class="bi bi-map me-1"></i> Modul 5 — Peta Interaktif
                </span>
            </div>
            <h3 class="fw-bold mb-1 text-dark">Peta Sebaran Laporan</h3>
            <p class="text-muted mb-0">
                Pantau persebaran laporan kebersihan di lingkungan kampus berdasarkan klaster lokasi dan laporan aktif.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="<?= site_url('/admin/ringkasan') ?>" class="btn btn-outline-success btn-touch">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard Ringkasan
            </a>
            <a href="<?= site_url('/admin/monitoring-petugas') ?>" class="btn btn-outline-secondary btn-touch">
                <i class="bi bi-people me-1"></i> Monitoring Petugas
            </a>
            <a href="<?= site_url('/admin/laporan') ?>" class="btn btn-outline-secondary btn-touch">
                <i class="bi bi-arrow-left me-1"></i> Rekap Laporan
            </a>
        </div>
    </div>

    <?php
    // Persiapan data agregasi ringkasan angka dari data yang dikirim controller
    $laporanList = $laporanPeta ?? [];
    $totalSemuaLaporanKoordinat = count($laporanList);

    $countMenunggu = 0;
    $countDiproses = 0;
    $countSelesai = 0;
    $countDibatalkan = 0;
    $areaOptions = [];

    foreach ($laporanList as $item) {
        $st = $item['status'] ?? '';
        if ($st === 'MENUNGGU') {
            $countMenunggu++;
        } elseif ($st === 'DIPROSES') {
            $countDiproses++;
        } elseif ($st === 'SELESAI') {
            $countSelesai++;
        } elseif ($st === 'DIBATALKAN') {
            $countDibatalkan++;
        }

        $locName = trim($item['nama_lokasi'] ?? '');
        if ($locName !== '') {
            $areaOptions[$locName] = $locName;
        }
    }
    ksort($areaOptions);
    $totalLaporanAktif = $countMenunggu + $countDiproses;
    ?>

    <!-- RINGKASAN ANGKA STATUS & FILTER AREA -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Laporan Berkoordinat (Fitur Bawaan yang Dipertahankan) -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded p-3">
                            <i class="bi bi-geo-alt fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Total Berkoordinat</p>
                            <h3 class="fw-bold mb-0 text-dark" id="statTotalKoordinat">
                                <?= $totalSemuaLaporanKoordinat ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Laporan Aktif (Menunggu + Diproses) -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid var(--eco-green) !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-eco-soft text-eco-primary rounded p-3">
                            <i class="bi bi-exclamation-triangle fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Laporan Aktif</p>
                            <h3 class="fw-bold mb-0 text-eco-primary" id="statLaporanAktif">
                                <?= $totalLaporanAktif ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Menunggu -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded p-3">
                            <i class="bi bi-hourglass-split fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Menunggu Petugas</p>
                            <h3 class="fw-bold mb-0 text-warning" id="statMenunggu">
                                <?= $countMenunggu ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Diproses -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
                            <i class="bi bi-gear-wide-connected fs-3"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Sedang Diproses</p>
                            <h3 class="fw-bold mb-0 text-primary" id="statDiproses">
                                <?= $countDiproses ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER DROPDOWN AREA KAMPUS -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row align-items-center g-3">
                <div class="col-md-5 col-lg-4">
                    <label for="filterAreaKampus" class="form-label fw-bold mb-1">
                        <i class="bi bi-funnel text-eco-primary me-1"></i> Filter Area Kampus:
                    </label>
                    <select id="filterAreaKampus" class="form-select form-select-lg" style="min-height: 44px; font-size: 0.95rem;">
                        <option value="">-- Semua Area Kampus --</option>
                        <?php foreach ($areaOptions as $area): ?>
                            <option value="<?= esc($area) ?>"><?= esc($area) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-7 col-lg-8">
                    <div class="d-flex flex-wrap align-items-center justify-content-md-end gap-2 text-muted small pt-md-3">
                        <span><i class="bi bi-info-circle me-1"></i>Klik marker di peta atau pilih dropdown untuk melihat daftar laporan aktif.</span>
                        <button type="button" id="btnResetFilter" class="btn btn-outline-secondary btn-sm btn-touch px-3">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PETA SEBARAN (KIRI) DAN PANEL LOKASI DIPILIH (KANAN) -->
    <div class="row g-4 mb-4">
        <!-- PETA UTAMA (KOLOM KIRI) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">
                        <i class="bi bi-map me-1"></i>
                        Peta Lokasi &amp; Marker Sebaran Laporan
                    </h5>
                    <span class="badge bg-white text-success fw-bold">
                        <span id="badgeJumlahMarker"><?= count($areaOptions) ?></span> Titik Lokasi
                    </span>
                </div>

                <div class="card-body p-3">
                    <?php if (!empty($laporanList)): ?>
                        <div id="petaLaporan"></div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-geo-alt text-muted" style="font-size: 50px;"></i>
                            <h5 class="mt-3">Belum Ada Lokasi Laporan</h5>
                            <p class="text-muted mb-0">
                                Belum ada laporan yang memiliki koordinat lokasi.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- PANEL KANAN: LOKASI DIPILIH -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm panel-lokasi-wrap">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-pin-map-fill text-eco-primary me-1"></i>
                            LOKASI DIPILIH
                        </h6>
                        <small class="text-muted" id="panelSubJudul">Daftar laporan aktif di lokasi terpilih</small>
                    </div>
                    <span class="badge bg-eco-soft text-eco-primary border" id="panelBadgeAktif">
                        0 Aktif
                    </span>
                </div>

                <div class="card-body p-3 panel-lokasi-body" id="panelLokasiKonten">
                    <!-- Default state saat belum ada lokasi yang diklik -->
                    <div class="text-center py-5 px-3 text-muted" id="placeholderBelumPilih">
                        <div class="bg-eco-soft text-eco-primary rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-cursor fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Belum Ada Lokasi Dipilih</h6>
                        <p class="small text-muted mb-0">
                            Klik salah satu titik marker pada peta atau pilih dari dropdown filter untuk melihat seluruh laporan aktif di area tersebut.
                        </p>
                    </div>

                    <!-- Container laporan aktif lokasi terpilih (tersembunyi secara default) -->
                    <div id="daftarLaporanLokasi" class="d-none">
                        <div class="bg-light p-2 rounded mb-3 border">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-dark text-truncate" id="namaLokasiTerpilih">-</span>
                                <span class="badge bg-warning text-dark" id="countLaporanTerpilih">0 Laporan</span>
                            </div>
                        </div>

                        <div id="listItemsLaporan" class="d-flex flex-column gap-2">
                            <!-- Diisi secara dinamis oleh JavaScript -->
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light py-2 text-center text-muted small border-top">
                    <i class="bi bi-shield-check me-1"></i> Data diperbarui secara real-time dari database.
                </div>
            </div>
        </div>
    </div>

    <!-- KETERANGAN STATUS LAPORAN (Fitur Bawaan yang Dipertahankan) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bi bi-palette text-eco-primary me-2"></i>
                Keterangan Status Laporan &amp; Marker
            </h5>
        </div>

        <div class="card-body pt-0">
            <div class="d-flex flex-wrap gap-4 align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark px-3 py-2">MENUNGGU</span>
                    <small class="text-muted">Laporan baru, belum diambil oleh petugas kebersihan</small>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary px-3 py-2">DIPROSES</span>
                    <small class="text-muted">Telah ditugaskan dan sedang dikerjakan petugas</small>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success px-3 py-2">SELESAI</span>
                    <small class="text-muted">Pembersihan selesai dan telah divalidasi</small>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger px-3 py-2">DIBATALKAN</span>
                    <small class="text-muted">Dibatalkan oleh Administrator</small>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<?php if (!empty($laporanList)): ?>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Data mentah seluruh laporan dari controller
    const rawLaporan = <?= json_encode(
        $laporanList,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
    ) ?>;

    // Kelompokkan data per lokasi berdasarkan nama_lokasi & koordinat rata-rata
    const dataLokasiGrouped = {};

    rawLaporan.forEach(function(item) {
        const lat = Number(item.latitude);
        const lng = Number(item.longitude);

        if (!Number.isFinite(lat) || !Number.isFinite(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
            return;
        }

        const locKey = (item.nama_lokasi && item.nama_lokasi.trim() !== '') ? item.nama_lokasi.trim() : `Koord (${lat.toFixed(4)}, ${lng.toFixed(4)})`;

        if (!dataLokasiGrouped[locKey]) {
            dataLokasiGrouped[locKey] = {
                nama_lokasi: locKey,
                latitude: lat,
                longitude: lng,
                laporanList: [],
                countTotal: 0,
                countAktif: 0,
                countMenunggu: 0,
                countDiproses: 0,
                countSelesai: 0,
                countDibatalkan: 0
            };
        }

        dataLokasiGrouped[locKey].laporanList.push(item);
        dataLokasiGrouped[locKey].countTotal++;

        if (item.status === 'MENUNGGU') {
            dataLokasiGrouped[locKey].countMenunggu++;
            dataLokasiGrouped[locKey].countAktif++;
        } else if (item.status === 'DIPROSES') {
            dataLokasiGrouped[locKey].countDiproses++;
            dataLokasiGrouped[locKey].countAktif++;
        } else if (item.status === 'SELESAI') {
            dataLokasiGrouped[locKey].countSelesai++;
        } else if (item.status === 'DIBATALKAN') {
            dataLokasiGrouped[locKey].countDibatalkan++;
        }
    });

    // Inisialisasi Leaflet Map
    const peta = L.map('petaLaporan').setView([-7.8, 110.4], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(peta);

    // Kumpulan marker per lokasi
    const markerMap = {};
    const markerGroup = L.featureGroup();

    function warnaBerdasarkanStatus(countMenunggu, countDiproses, countAktif) {
        if (countMenunggu > 0) return '#ffc107'; // Ada yang menunggu: kuning
        if (countDiproses > 0) return '#0d6efd'; // Sedang diproses: biru
        if (countAktif > 0) return '#15803D';
        return '#6c757d'; // Selesai / Dibatalkan: abu-abu
    }

    // Bangun marker untuk setiap lokasi
    Object.keys(dataLokasiGrouped).forEach(function(locKey) {
        const loc = dataLokasiGrouped[locKey];

        const fillColor = warnaBerdasarkanStatus(loc.countMenunggu, loc.countDiproses, loc.countAktif);
        const radiusSize = loc.countAktif > 0 ? Math.min(18, 10 + loc.countAktif * 2) : 9;

        // Custom circle marker
        const marker = L.circleMarker([loc.latitude, loc.longitude], {
            radius: radiusSize,
            fillColor: fillColor,
            color: '#ffffff',
            weight: 2.5,
            opacity: 1,
            fillOpacity: 0.95
        });

        // Popup info
        const popupHtml = `
            <div class="info-peta" style="min-width: 200px;">
                <h6 class="fw-bold mb-1 text-dark">${escapeHtml(loc.nama_lokasi)}</h6>
                <hr class="my-1">
                <div class="d-flex justify-content-between mb-1">
                    <span>Laporan Aktif:</span>
                    <strong class="${loc.countAktif > 0 ? 'text-danger' : 'text-success'}">${loc.countAktif}</strong>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Menunggu: ${loc.countMenunggu}</span>
                    <span>Diproses: ${loc.countDiproses}</span>
                </div>
                <div class="d-flex justify-content-between small text-muted mb-2">
                    <span>Selesai: ${loc.countSelesai}</span>
                    <span>Total: ${loc.countTotal}</span>
                </div>
                <button type="button" class="btn btn-sm btn-success w-100 btn-touch"
                        onclick="pilihLokasi('${encodeURIComponent(locKey)}')">
                    <i class="bi bi-eye me-1"></i> Buka Panel Lokasi
                </button>
            </div>
        `;

        marker.bindPopup(popupHtml);

        marker.on('click', function() {
            pilihLokasi(locKey);
        });

        markerMap[locKey] = marker;
        markerGroup.addLayer(marker);
    });

    if (Object.keys(dataLokasiGrouped).length > 0) {
        markerGroup.addTo(peta);
        peta.fitBounds(markerGroup.getBounds(), {
            padding: [40, 40],
            maxZoom: 16
        });
    }

    // Fungsi menampilkan detail lokasi di Panel Kanan "LOKASI DIPILIH"
    function pilihLokasi(locKey) {
        const decodedKey = decodeURIComponent(locKey);
        const loc = dataLokasiGrouped[decodedKey];
        if (!loc) return;

        // Update dropdown filter agar sinkron
        const dropdown = document.getElementById('filterAreaKampus');
        if (dropdown && dropdown.value !== decodedKey) {
            dropdown.value = decodedKey;
        }

        // Tampilkan container daftar dan sembunyikan placeholder
        const placeholder = document.getElementById('placeholderBelumPilih');
        const daftarContainer = document.getElementById('daftarLaporanLokasi');
        const namaLokasiEl = document.getElementById('namaLokasiTerpilih');
        const countBadgeEl = document.getElementById('countLaporanTerpilih');
        const panelBadgeAktif = document.getElementById('panelBadgeAktif');
        const panelSubJudul = document.getElementById('panelSubJudul');
        const listItemsEl = document.getElementById('listItemsLaporan');

        placeholder.classList.add('d-none');
        daftarContainer.classList.remove('d-none');

        namaLokasiEl.textContent = loc.nama_lokasi;
        countBadgeEl.textContent = `${loc.countAktif} Aktif / ${loc.countTotal} Total`;
        panelBadgeAktif.textContent = `${loc.countAktif} Laporan Aktif`;
        panelSubJudul.textContent = `Menampilkan laporan aktif di ${loc.nama_lokasi}`;

        // Filter laporan yang berstatus aktif (MENUNGGU atau DIPROSES)
        const laporanAktif = loc.laporanList.filter(function(r) {
            return r.status === 'MENUNGGU' || r.status === 'DIPROSES';
        });

        listItemsEl.innerHTML = '';

        if (laporanAktif.length > 0) {
            laporanAktif.forEach(function(lap) {
                const isMenunggu = lap.status === 'MENUNGGU';
                const badgeClass = isMenunggu ? 'bg-warning text-dark' : 'bg-primary text-white';
                const statusIcon = isMenunggu ? 'bi-hourglass-split' : 'bi-gear-wide-connected';

                const cardHtml = `
                    <div class="lokasi-report-card p-3 border shadow-sm">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <span class="badge ${badgeClass}">
                                <i class="bi ${statusIcon} me-1"></i>${escapeHtml(lap.status)}
                            </span>
                            <span class="badge bg-light text-muted border font-monospace">#LP-${String(lap.id).padStart(4, '0')}</span>
                        </div>
                        <h6 class="fw-bold mb-1 text-dark text-truncate">${escapeHtml(lap.judul)}</h6>
                        <div class="text-muted small mb-1">
                            <i class="bi bi-person me-1"></i>Pelapor: <strong>${escapeHtml(lap.nama_pelapor || 'Anonim')}</strong>
                        </div>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-tag me-1"></i>Kategori: ${escapeHtml(lap.kategori)}
                        </div>
                        <a href="<?= site_url('/admin/laporan/detail/') ?>${encodeURIComponent(lap.id)}"
                           class="btn btn-outline-success btn-sm w-100 btn-touch"
                           title="Lihat Detail Laporan">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka Detail Laporan
                        </a>
                    </div>
                `;
                listItemsEl.insertAdjacentHTML('beforeend', cardHtml);
            });
        } else {
            listItemsEl.innerHTML = `
                <div class="text-center py-4 bg-light rounded text-muted">
                    <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                    <h6 class="fw-bold text-dark mb-1">Tidak Ada Laporan Aktif</h6>
                    <p class="small text-muted mb-0">Seluruh laporan di area ini telah selesai ditangani atau dibatalkan.</p>
                </div>
            `;
        }

        // Fokuskan peta ke marker yang dipilih
        if (markerMap[decodedKey]) {
            peta.setView([loc.latitude, loc.longitude], 16, { animate: true });
            markerMap[decodedKey].openPopup();
        }
    }

    // Handler Filter Dropdown Area Kampus
    document.getElementById('filterAreaKampus').addEventListener('change', function() {
        const areaSelected = this.value;

        if (!areaSelected) {
            // Tampilkan semua marker kembali
            markerGroup.clearLayers();
            Object.keys(markerMap).forEach(function(k) {
                markerGroup.addLayer(markerMap[k]);
            });
            if (Object.keys(dataLokasiGrouped).length > 0) {
                peta.fitBounds(markerGroup.getBounds(), { padding: [40, 40], maxZoom: 16 });
            }

            // Kembalikan placeholder panel kanan
            document.getElementById('placeholderBelumPilih').classList.remove('d-none');
            document.getElementById('daftarLaporanLokasi').classList.add('d-none');
            document.getElementById('panelBadgeAktif').textContent = '0 Aktif';
            document.getElementById('panelSubJudul').textContent = 'Daftar laporan aktif di lokasi terpilih';
            return;
        }

        // Jika satu area dipilih, fokuskan dan buka panel lokasi
        if (dataLokasiGrouped[areaSelected]) {
            pilihLokasi(areaSelected);
        }
    });

    // Reset Filter Button
    document.getElementById('btnResetFilter').addEventListener('click', function() {
        document.getElementById('filterAreaKampus').value = '';
        document.getElementById('filterAreaKampus').dispatchEvent(new Event('change'));
    });

    // Helper sanitasi HTML
    function escapeHtml(teks) {
        return String(teks ?? '').replace(/[&<>"']/g, function(karakter) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[karakter];
        });
    }
</script>

<?php endif; ?>

<?= $this->endSection() ?>
