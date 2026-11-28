const DB_NAME = 'LaporBersihKampusDB';
const DB_VERSION = 1;
const STORE_NAME = 'laporan_pending';

// Membuka database lokal
function bukaDatabaseOffline() {
    return new Promise((resolve, reject) => {

        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onupgradeneeded = function (event) {
            const db = event.target.result;

            if (!db.objectStoreNames.contains(STORE_NAME)) {
                const store = db.createObjectStore(STORE_NAME, {
                    keyPath: 'id',
                    autoIncrement: true
                });

                store.createIndex('waktu_simpan', 'waktu_simpan', {
                    unique: false
                });
            }
        };

        request.onsuccess = function (event) {
            resolve(event.target.result);
        };

        request.onerror = function () {
            reject(request.error);
        };

    });
}

// Menyimpan laporan ke database lokal
async function simpanLaporanOffline(dataLaporan) {

    const db = await bukaDatabaseOffline();

    return new Promise((resolve, reject) => {

        const transaction = db.transaction(STORE_NAME, 'readwrite');

        const store = transaction.objectStore(STORE_NAME);

        const data = {
            ...dataLaporan,
            waktu_simpan: new Date().toISOString(),
            status_sinkronisasi: 'MENUNGGU'
        };

        const request = store.add(data);

        request.onsuccess = function () {
            resolve(request.result);
        };

        request.onerror = function () {
            reject(request.error);
        };

        transaction.oncomplete = function () {
            db.close();
        };

        transaction.onerror = function () {
            db.close();
        };

    });
}

// Mengambil semua laporan yang belum dikirim
async function ambilLaporanOffline() {

    const db = await bukaDatabaseOffline();

    return new Promise((resolve, reject) => {

        const transaction = db.transaction(STORE_NAME, 'readonly');

        const store = transaction.objectStore(STORE_NAME);

        const request = store.getAll();

        request.onsuccess = function () {
            resolve(request.result);
            db.close();
        };

        request.onerror = function () {
            reject(request.error);
            db.close();
        };

    });
}

// Menghapus laporan setelah berhasil dikirim
async function hapusLaporanOffline(id) {

    const db = await bukaDatabaseOffline();

    return new Promise((resolve, reject) => {

        const transaction = db.transaction(STORE_NAME, 'readwrite');

        const store = transaction.objectStore(STORE_NAME);

        const request = store.delete(id);

        request.onsuccess = function () {
            resolve(true);
        };

        request.onerror = function () {
            reject(request.error);
        };

        transaction.oncomplete = function () {
            db.close();
        };

        transaction.onerror = function () {
            db.close();
        };

    });
}