const CACHE_NAME = 'lapor-bersih-kampus-v3';

const OFFLINE_URL = '/offline.html';

// File penting yang disimpan di cache browser.
const FILES_TO_CACHE = [
    OFFLINE_URL,
    '/offline-storage.js',
    '/offline-sync.js'
];


// ==========================================
// INSTALL SERVICE WORKER
// ==========================================

self.addEventListener('install', event => {

    event.waitUntil(

        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(FILES_TO_CACHE);
            })
            .then(() => self.skipWaiting())

    );

});


// ==========================================
// ACTIVATE SERVICE WORKER
// ==========================================

self.addEventListener('activate', event => {

    event.waitUntil(

        caches.keys()
            .then(cacheNames => {

                return Promise.all(

                    cacheNames.map(cacheName => {

                        // Hapus cache versi lama milik aplikasi ini.
                        if (
                            cacheName.startsWith('lapor-bersih-kampus-') &&
                            cacheName !== CACHE_NAME
                        ) {
                            return caches.delete(cacheName);
                        }

                    })

                );

            })
            .then(() => self.clients.claim())

    );

});


// ==========================================
// FETCH REQUEST
// ==========================================

self.addEventListener('fetch', event => {

    const request = event.request;

    // Hanya tangani permintaan GET.
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    // Abaikan permintaan ke domain lain.
    if (url.origin !== self.location.origin) {
        return;
    }

    // Halaman: coba ambil dari server terlebih dahulu.
    if (request.mode === 'navigate') {

        event.respondWith(

            fetch(request)
                .catch(() => {
                    return caches.match(OFFLINE_URL);
                })

        );

        return;
    }

    // Hanya cache file statis.
    const jenisFileStatis = [
        'script',
        'style',
        'image',
        'font'
    ];

    if (!jenisFileStatis.includes(request.destination)) {
        return;
    }

    // Gunakan cache jika tersedia.
    event.respondWith(

        caches.match(request)
            .then(cachedResponse => {

                if (cachedResponse) {
                    return cachedResponse;
                }

                return fetch(request)
                    .then(networkResponse => {

                        if (
                            networkResponse &&
                            networkResponse.status === 200
                        ) {

                            const responseClone = networkResponse.clone();

                            caches.open(CACHE_NAME)
                                .then(cache => {
                                    cache.put(request, responseClone);
                                });

                        }

                        return networkResponse;

                    });

            })

    );

});