// eFarm Service Worker
const CACHE_VERSION = 'efarm-v1';
const APP_BASE = self.location.pathname.replace('sw.js', '');

const STATIC_ASSETS = [
    APP_BASE + 'offline.html',
    APP_BASE + 'icons/icon.svg',
    APP_BASE + 'icons/icon-maskable.svg',
    APP_BASE + 'admin/style.css',
    APP_BASE + 'admin/javascript.js',
    APP_BASE + 'user/style.css',
    APP_BASE + 'user/javascript.js',
];

// CDN assets to cache on first use
const CDN_HOSTS = [
    'cdn.jsdelivr.net',
    'cdnjs.cloudflare.com',
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_VERSION)
            .then(cache => cache.addAll(STATIC_ASSETS))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(
                keys.filter(k => k !== CACHE_VERSION).map(k => caches.delete(k))
            ))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Never intercept POST requests or auth endpoints
    if (request.method !== 'GET') return;
    if (url.pathname.includes('/function/')) return;

    // CDN assets: cache-first (they are versioned and rarely change)
    if (CDN_HOSTS.includes(url.hostname)) {
        event.respondWith(cacheFirst(request));
        return;
    }

    // Local static assets (CSS, JS, icons, images): cache-first
    if (/\.(css|js|svg|png|jpg|jpeg|gif|woff2?|ico)$/.test(url.pathname)) {
        event.respondWith(cacheFirst(request));
        return;
    }

    // PHP pages: network-first, fallback to offline page
    if (url.pathname.endsWith('.php') || url.pathname.endsWith('/')) {
        event.respondWith(networkFirstWithOfflineFallback(request));
        return;
    }
});

async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) return cached;
    try {
        const response = await fetch(request);
        if (response.ok || response.type === 'opaque') {
            const cache = await caches.open(CACHE_VERSION);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        return new Response('Asset unavailable offline.', { status: 503 });
    }
}

async function networkFirstWithOfflineFallback(request) {
    try {
        const response = await fetch(request);
        return response;
    } catch {
        const cached = await caches.match(APP_BASE + 'offline.html');
        return cached || new Response('<h1>You are offline</h1>', {
            headers: { 'Content-Type': 'text/html' },
        });
    }
}
