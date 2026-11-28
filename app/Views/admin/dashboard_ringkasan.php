<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<!-- CDN Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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

    .bg-eco-dark {
        background-color: var(--eco-dark-green) !important;
        color: #ffffff !important;
    }

    .bg-eco-soft {
        background-color: var(--eco-light-green) !important;
    }

    .text-eco-primary {
        color: var(--eco-green) !important;
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

    .btn-eco-outline {
        border: 1.5px solid var(--eco-green);
        color: var(--eco-green);
        background-color: transparent;
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn-eco-outline:hover {
        background-color: var(--eco-green);
        color: #ffffff;
    }

    .btn-touch {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .kpi-card {
        border-radius: 12px;
        border: 1px solid rgba(21, 128, 61, 0.12);
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(21, 128, 61, 0.12);
    }

    .kpi-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    #miniPetaLaporan {
        width: 100%;
        height: 380px;
        border-radius: 10px;
        z-index: 1;
    }

    .chart-container {
        position: relative;
        height: 320px;
        width: 100%;
    }

    .table-laporan th {
        background-color: var(--eco-light-green);
        color: var(--eco-dark-green);
        font-weight: 600;
        font-size: 0.9rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-2">

    <!-- HEADER DASHBOARD -->
    <div class="content-card mb-4 bg-eco-soft border-0">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-eco-primary mb-2 px-3 py-2">
                    <i class="bi bi-shield-check me-1"></i> Modul 5 — Admin EcoCampus
                </span>
                <h3 class="fw-bold mb-1 text-dark">
                    <?= esc($judul ?? 'Dashboard Ringkasan Admin') ?>
                </h3>
                <p class="text-muted mb-0">
                    Ringkasan performa pelaporan, kecepatan penanganan petugas, dan sebaran laporan kebersihan kampus.
                </p>
            </div>

            <!-- Tombol Navigasi Cepat Modul 5 -->
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= site_url('admin/peta-laporan') ?>" class="btn btn-eco-outline">
                    <i class="bi bi-map me-1"></i> Peta Sebaran
                </a>
                <a href="<?= site_url('admin/monitoring-petugas') ?>" class="btn btn-eco-primary">
                    <i class="bi bi-people me-1"></i> Monitoring Petugas
                </a>
                <a href="<?= site_url('admin/laporan') ?>" class="btn btn-outline-secondary btn-touch">
                    <i class="bi bi-clipboard-data me-1"></i> Rekap Laporan
                </a>
            </div>
        </div>
    </div>

    <!-- 5 KARTU KPI BOOTSTRAP -->
    <div class="row g-3 mb-4">
        <!-- 1. Menunggu -->
        <div class="col-sm-6 col-xl">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Menunggu</span>
                        <h2 class="fw-bold text-warning mb-0">
                            <?= esc($kpi['menunggu'] ?? 0) ?>
                        </h2>
                    </div>
                    <div class="kpi-icon-wrap bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="bi bi-info-circle me-1"></i>Belum diambil petugas
                </div>
            </div>
        </div>

        <!-- 2. Diproses -->
        <div class="col-sm-6 col-xl">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Diproses</span>
                        <h2 class="fw-bold text-primary mb-0">
                            <?= esc($kpi['diproses'] ?? 0) ?>
                        </h2>
                    </div>
                    <div class="kpi-icon-wrap bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="bi bi-person-walking me-1"></i>Sedang ditangani
                </div>
            </div>
        </div>

        <!-- 3. Selesai -->
        <div class="col-sm-6 col-xl">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Selesai</span>
                        <h2 class="fw-bold text-success mb-0">
                            <?= esc($kpi['selesai'] ?? 0) ?>
                        </h2>
                    </div>
                    <div class="kpi-icon-wrap bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="bi bi-check-all me-1"></i>Tuntas dibersihkan
                </div>
            </div>
        </div>

        <!-- 4. Dibatalkan -->
        <div class="col-sm-6 col-xl">
            <div class="kpi-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Dibatalkan</span>
                        <h2 class="fw-bold text-danger mb-0">
                            <?= esc($kpi['dibatalkan'] ?? 0) ?>
                        </h2>
                    </div>
                    <div class="kpi-icon-wrap bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-x-circle"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="bi bi-slash-circle me-1"></i>Laporan tidak valid
                </div>
            </div>
        </div>

        <!-- 5. Rata-rata Respon -->
        <div class="col-sm-12 col-xl">
            <div class="kpi-card p-3 h-100" style="border-left: 4px solid var(--eco-green);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-medium d-block mb-1">Rata-rata Respon</span>
                        <h2 class="fw-bold text-eco-primary mb-0">
                            <?= esc($kpi['rata_rata_respon'] ?? 0) ?> <span class="fs-6 fw-normal text-muted">mnt</span>
                        </h2>
                    </div>
                    <div class="kpi-icon-wrap bg-eco-soft text-eco-primary">
                        <i class="bi bi-stopwatch"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="badge bg-success bg-opacity-10 text-success py-1 px-2 fw-semibold">
                        Target &lt; 30 menit
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 2: GRAFIK 7 HARI TERAKHIR & MINI PETA LEAFLET -->
    <div class="row g-4 mb-4">
        <!-- GRAFIK BATANG LAPORAN 7 HARI TERAKHIR -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-bar-chart-line text-eco-primary me-2"></i>
                            Laporan 7 Hari Terakhir
                        </h5>
                        <small class="text-muted">Tren volume laporan kebersihan yang masuk</small>
                    </div>
                    <span class="badge bg-eco-soft text-eco-primary border">Warna #15803D</span>
                </div>
                <div class="card-body pt-1">
                    <div class="chart-container">
                        <canvas id="chartLaporanMingguan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- MINI PETA LEAFLET (LAPORAN AKTIF) -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-geo-alt text-eco-primary me-2"></i>
                            Mini Peta Laporan Aktif
                        </h5>
                        <small class="text-muted">Sebaran laporan berstatus Menunggu &amp; Diproses</small>
                    </div>
                    <a href="<?= site_url('admin/peta-laporan') ?>" class="btn btn-sm btn-outline-secondary btn-touch">
                        Buka Peta Penuh <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-3">
                    <?php if (!empty($laporanAktifPeta)): ?>
                        <div id="miniPetaLaporan"></div>
                    <?php else: ?>
                        <div class="text-center py-5 bg-eco-soft rounded">
                            <i class="bi bi-check-circle text-eco-primary" style="font-size: 42px;"></i>
                            <h6 class="fw-bold mt-2 mb-1">Tidak Ada Laporan Aktif</h6>
                            <p class="text-muted small mb-0">Belum ada laporan aktif (Menunggu/Diproses) yang memiliki titik koordinat.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 3: TABEL 5 LAPORAN TERBARU -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-clock-history text-eco-primary me-2"></i>
                    5 Laporan Terbaru
                </h5>
                <small class="text-muted">Aktivitas pelaporan terkini yang masuk ke sistem</small>
            </div>
            <a href="<?= site_url('admin/laporan') ?>" class="btn btn-eco-outline btn-sm">
                Lihat Semua Laporan <i class="bi bi-chevron-right ms-1"></i>
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-laporan">
                    <thead>
                        <tr>
                            <th class="ps-3 py-3" style="width: 130px;">Kode</th>
                            <th class="py-3">Lokasi &amp; Deskripsi</th>
                            <th class="py-3" style="width: 180px;">Pelapor</th>
                            <th class="py-3 text-center" style="width: 140px;">Status</th>
                            <th class="pe-3 py-3 text-end" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($laporanTerbaru)): ?>
                            <?php foreach ($laporanTerbaru as $lap): ?>
                                <?php
                                $statusBadge = [
                                    'MENUNGGU'   => ['bg' => 'warning', 'text' => 'dark', 'label' => 'Menunggu'],
                                    'DIPROSES'   => ['bg' => 'primary', 'text' => 'white', 'label' => 'Diproses'],
                                    'SELESAI'    => ['bg' => 'success', 'text' => 'white', 'label' => 'Selesai'],
                                    'DIBATALKAN' => ['bg' => 'danger',  'text' => 'white', 'label' => 'Dibatalkan'],
                                ];
                                $st = $statusBadge[$lap['status']] ?? ['bg' => 'secondary', 'text' => 'white', 'label' => $lap['status']];
                                ?>
                                <tr>
                                    <!-- KODE -->
                                    <td class="ps-3">
                                        <span class="badge bg-light text-dark border font-monospace fw-bold">
                                            #LP-<?= sprintf('%04d', $lap['id']) ?>
                                        </span>
                                        <div class="text-muted" style="font-size: 11px;">
                                            <?= date('d M Y, H:i', strtotime($lap['created_at'])) ?>
                                        </div>
                                    </td>

                                    <!-- LOKASI & DESKRIPSI -->
                                    <td>
                                        <div class="fw-semibold text-dark mb-1">
                                            <i class="bi bi-geo-alt text-eco-primary me-1"></i>
                                            <?= esc($lap['nama_lokasi'] ?: 'Lokasi tidak disebutkan') ?>
                                        </div>
                                        <div class="text-muted small text-truncate" style="max-width: 460px;">
                                            <?= esc($lap['judul']) ?> &mdash; <?= esc(mb_strimwidth($lap['deskripsi'], 0, 80, '...')) ?>
                                        </div>
                                    </td>

                                    <!-- PELAPOR -->
                                    <td>
                                        <div class="fw-medium text-dark">
                                            <?= esc($lap['nama_pelapor'] ?? 'Anonim') ?>
                                        </div>
                                        <span class="badge bg-light text-muted border" style="font-size: 11px;">
                                            <?= esc($lap['kategori']) ?>
                                        </span>
                                    </td>

                                    <!-- STATUS -->
                                    <td class="text-center">
                                        <span class="badge bg-<?= $st['bg'] ?> text-<?= $st['text'] ?> px-2 py-1">
                                            <?= $st['label'] ?>
                                        </span>
                                    </td>

                                    <!-- AKSI -->
                                    <td class="pe-3 text-end">
                                        <a href="<?= site_url('admin/laporan/detail/' . $lap['id']) ?>"
                                           class="btn btn-outline-success btn-sm btn-touch px-3"
                                           title="Lihat Detail Laporan">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada data laporan yang tercatat.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- CDN Chart.js & Leaflet.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // 1. Inisialisasi Grafik Batang Chart.js
    (function initChart() {
        const ctx = document.getElementById('chartLaporanMingguan');
        if (!ctx) return;

        const labels = <?= json_encode($grafikLabels ?? []) ?>;
        const dataValues = <?= json_encode($grafikData ?? []) ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: dataValues,
                    backgroundColor: '#15803D',
                    hoverBackgroundColor: '#14532D',
                    borderRadius: 6,
                    maxBarThickness: 38
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` Laporan: ${context.parsed.y} berkas`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 12 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    })();

    // 2. Inisialisasi Mini Peta Leaflet untuk Laporan Aktif (MENUNGGU & DIPROSES)
    (function initMiniMap() {
        const mapContainer = document.getElementById('miniPetaLaporan');
        if (!mapContainer) return;

        const dataAktif = <?= json_encode(
            $laporanAktifPeta ?? [],
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
        ) ?>;

        const miniMap = L.map('miniPetaLaporan').setView([-7.8, 110.4], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(miniMap);

        const markers = [];

        function getMarkerColor(status) {
            return status === 'MENUNGGU' ? '#ffc107' : '#0d6efd';
        }

        dataAktif.forEach(function(item) {
            const lat = Number(item.latitude);
            const lng = Number(item.longitude);

            if (!Number.isFinite(lat) || !Number.isFinite(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                return;
            }

            const marker = L.circleMarker([lat, lng], {
                radius: 8,
                fillColor: getMarkerColor(item.status),
                color: '#ffffff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.95
            }).addTo(miniMap);

            const popupContent = `
                <div style="font-size: 13px; line-height: 1.5; min-width: 170px;">
                    <div class="fw-bold mb-1">${escapeHtml(item.judul)}</div>
                    <span class="badge ${item.status === 'MENUNGGU' ? 'bg-warning text-dark' : 'bg-primary'} mb-2">
                        ${escapeHtml(item.status)}
                    </span>
                    <div class="text-muted small mb-2">
                        <i class="bi bi-geo-alt"></i> ${escapeHtml(item.nama_lokasi || 'Area Kampus')}
                    </div>
                    <a href="<?= site_url('/admin/laporan/detail/') ?>${encodeURIComponent(item.id)}"
                       class="btn btn-sm btn-success w-100 text-white" style="min-height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                        Lihat Detail
                    </a>
                </div>
            `;
            marker.bindPopup(popupContent);
            markers.push(marker);
        });

        if (markers.length > 0) {
            const group = L.featureGroup(markers);
            miniMap.fitBounds(group.getBounds(), {
                padding: [30, 30],
                maxZoom: 16
            });
        }

        function escapeHtml(text) {
            return String(text ?? '').replace(/[&<>"']/g, function(c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[c];
            });
        }
    })();
</script>
<?= $this->endSection() ?>
