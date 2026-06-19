const CACHE = 'schoolcoreapp-v2';

self.addEventListener('install', () => self.skipWaiting());

self.addEventListener('activate', (e) => {
    e.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (e) => {
    if (e.request.method !== 'GET') return;

    // Livewire siempre a la red
    if (e.request.url.includes('/livewire/')) return;

    // Vite build assets (/build/) tienen hash de contenido y HTTP cache propio.
    // NO los interceptamos para evitar servir versiones obsoletas.
    if (e.request.url.includes('/build/')) return;

    // Navegación (HTML): red primero, cache como fallback offline
    if (e.request.mode === 'navigate') {
        e.respondWith(
            fetch(e.request).catch(() =>
                caches.match(e.request).then((cached) => cached || fetch(e.request))
            )
        );
        return;
    }

    // Fuentes y recursos estáticos sin hash: cache-first con update en background
    if (/\.(woff2?|png|jpg|svg|ico)(\?.*)?$/.test(e.request.url)) {
        e.respondWith(
            caches.match(e.request).then((cached) => {
                const network = fetch(e.request).then((res) => {
                    const clone = res.clone();
                    caches.open(CACHE).then((c) => c.put(e.request, clone));
                    return res;
                });
                return cached || network;
            })
        );
    }
});
