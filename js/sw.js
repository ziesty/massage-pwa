const CACHE_NAME = 'massage-cache-v2';
const ASSETS = [
    '/',
    '/index.html',
    '/css/style.css',
    '/js/app.js',
    '/manifest.json',
    '/icon-192.png',
    '/icon-512.png'
];

// Установка и кэширование
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(ASSETS))
            .then(() => self.skipWaiting())
    );
});

// Стратегия: Сначала кэш, потом сеть
self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request)
            .then(cachedResponse => {
                return cachedResponse || fetch(event.request)
                    .then(response => {
                        // Кэшируем новые запросы
                        const clone = response.clone();
                        caches.open(CACHE_NAME)
                            .then(cache => cache.put(event.request, clone));
                        return response;
                    })
                    .catch(() => {
                        // Fallback для ошибок
                        if (event.request.mode === 'navigate') {
                            return caches.match('/offline.html');
                        }
                        return new Response('Оффлайн-режим');
                    });
            })
    );
});

// Очистка старых кэшей
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.map(key =>
                    key !== CACHE_NAME ? caches.delete(key) : null
                )
            )
        ).then(() => self.clients.claim())
    );
});