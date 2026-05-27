const CACHE_NAME = 'hris-pmc-v1';
const STATIC_ASSETS = [
    '/',
    '/manifest.json',
    '/build/assets/app.css',
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap',
];

// Install — cache static assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS.filter(url => !url.startsWith('http')));
        }).catch(() => {
            // Silently fail if some assets can't be cached
        })
    );
    self.skipWaiting();
});

// Activate — cleanup old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

// Fetch — network-first strategy
self.addEventListener('fetch', (event) => {
    // Skip non-GET requests and browser-extension/chrome URLs
    if (event.request.method !== 'GET' || !event.request.url.startsWith('http')) return;

    // Skip POST requests (attendance, login)
    const url = new URL(event.request.url);
    const skipPaths = ['/login', '/logout', '/clock/in', '/clock/out', '/submissions'];
    if (skipPaths.some(p => url.pathname.includes(p))) return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Cache successful static responses
                if (response.ok && (
                    event.request.url.includes('/build/') ||
                    event.request.url.includes('fonts.googleapis.com')
                )) {
                    const resClone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, resClone));
                }
                return response;
            })
            .catch(() => {
                // Fallback to cache when offline
                return caches.match(event.request).then(cached => {
                    if (cached) return cached;
                    return caches.match('/').then(fallback => {
                        return fallback || new Response('Koneksi internet terputus.', {
                            status: 503,
                            statusText: 'Service Unavailable',
                            headers: { 'Content-Type': 'text/plain; charset=utf-8' }
                        });
                    });
                });
            })
    );
});
