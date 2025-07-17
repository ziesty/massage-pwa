// Инициализация приложения
document.addEventListener('DOMContentLoaded', () => {
  // Проверка поддержки PWA
  if (!('serviceWorker' in navigator)) {
    console.error('PWA не поддерживается в этом браузере');
    return;
  }

  // Регистрация Service Worker
  registerServiceWorker();

  // Инициализация компонентов
  initFeedbackForm();
  initInstallPrompt();
});

// Регистрация Service Worker
function registerServiceWorker() {
  navigator.serviceWorker.register('/sw.js')
    .then(registration => {
      console.log('SW зарегистрирован:', registration.scope);
      registration.update(); // Проверка обновлений
    })
    .catch(error => {
      console.error('Ошибка регистрации SW:', error);
    });
}

// Работа с формой отзывов
function initFeedbackForm() {
  const form = document.getElementById('feedback-form');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // Сохранение в IndexedDB или отправка на сервер
    await saveFeedback(data);
    form.reset();
    
    alert('Отзыв сохранён!');
  });
}

// Логика установки PWA
function initInstallPrompt() {
  let deferredPrompt;

  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    document.getElementById('install-btn').style.display = 'block';
  });

  document.getElementById('install-btn').addEventListener('click', () => {
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then(choice => {
      if (choice.outcome === 'accepted') {
        console.log('Пользователь установил PWA');
      }
    });
  });
}

// Сохранение отзывов (заглушка)
async function saveFeedback(data) {
  // Реальная реализация может использовать IndexedDB или fetch()
  console.log('Сохранение отзыва:', data);
  return new Promise(resolve => setTimeout(resolve, 500));
}

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
      .then(reg => console.log('SW registered'))
      .catch(err => console.log('SW registration failed: ', err));
  });
}