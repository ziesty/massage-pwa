// 1. Инициализация PWA
document.addEventListener('DOMContentLoaded', () => {
    initApp();
});

function initApp() {
    // 2. Проверка поддержки PWA
    if (!('serviceWorker' in navigator)) {
        alert('Ваш браузер не поддерживает PWA!');
        return;
    }

    // 3. Работа с отзывами (пример)
    const feedbackForm = document.getElementById('feedback-form');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', handleSubmit);
    }

    // 4. Проверка оффлайн-режима
    updateOnlineStatus();
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
}

// Обработка формы отзыва
function handleSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);

    // Здесь можно добавить отправку данных или сохранение локально
    console.log('Отправлено:', Object.fromEntries(formData));
    e.target.reset();
}

// Показ статуса сети
function updateOnlineStatus() {
    const statusElement = document.getElementById('network-status');
    if (statusElement) {
        statusElement.textContent = navigator.onLine ? 'Онлайн' : 'Оффлайн';
    }
}

let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    document.getElementById('install-btn').style.display = 'block';
});

document.getElementById('install-btn').addEventListener('click', () => {
    deferredPrompt.prompt();
});