const CACHE_NAME = 'kataloque-cache-v1';
const urlsToCache = [
    '/',
    '/manifest.json',
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
});

self.addEventListener('fetch', event => {
    if (event.request.mode === 'navigate') {
        // Let the browser handle the navigation naturally to preserve SameSite cookies
        // and avoid auth middleware false-positives that cause redirect loops.
        return;
    }

    // Default strategy (Cache first) for assets
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                if (response) {
                    return response;
                }
                return fetch(event.request);
            })
    );
});
