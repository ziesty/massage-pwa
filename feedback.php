<?php
$host = 'localhost';
$dbname = 'massage_cabinet';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Ошибка подключения: ' . $conn->connect_error);
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $message = $_POST['message'];
    $rating1 = (int)$_POST['rating_atmosphere'];
    $rating2 = (int)$_POST['rating_sensation'];
    $rating3 = (int)$_POST['rating_overall'];

    $stmt = $conn->prepare("INSERT INTO feedback (name, message, rating_atmosphere, rating_sensation, rating_overall) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiii", $name, $message, $rating1, $rating2, $rating3);
    $stmt->execute();
    $stmt->close();
}

// Получение отзывов
$result = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Обратная связь | Время Тишины</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .feedback-form {
      max-width: 600px;
      margin: 2rem auto;
      background: #ffffff;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .feedback-form label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: #4f5d56;
    }
    .feedback-form input[type="text"] {
      width: 100%;
      padding: 0.8rem;
      margin-bottom: 1.5rem;
      border: 1px solid #ccc;
      border-radius: 0.7rem;
      font-family: 'Quicksand', sans-serif;
      font-size: 1rem;
      background-color: #f9f9f9;
      transition: border-color 0.3s ease;
    }
    .feedback-form textarea {
      width: 100%;
      padding: 0.8rem;
      margin-bottom: 1.5rem;
      border: 1px solid #ccc;
      border-radius: 0.7rem;
      font-family: 'Quicksand', sans-serif;
      font-size: 1rem;
      background-color: #f9f9f9;
      transition: border-color 0.3s ease;
      resize: none;
      min-height: 120px;
      max-height: 120px;
    }
    .feedback-form input:focus,
    .feedback-form textarea:focus {
      border-color: #a3cbb0;
      outline: none;
      box-shadow: 0 0 0 3px rgba(163, 203, 176, 0.2);
    }
    .rating-group {
      margin-bottom: 1.5rem;
    }
    .rating-bar-input {
      display: flex;
      gap: 5px;
    }
    .rating-bar-input div {
      width: 20%;
      height: 14px;
      background-color: #e0e0e0;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.2s ease;
      position: relative;
      overflow: hidden;
    }
    .rating-bar-input div::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #a3cbb0;
      opacity: 0;
      transition: opacity 0.2s ease;
    }
    .rating-bar-input div.hover::after,
    .rating-bar-input div.selected::after {
      opacity: 1;
    }
    .feedback-form .btn {
      background-color: #a3cbb0;
      color: #fff;
      padding: 0.8rem 2rem;
      border: none;
      border-radius: 0.7rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .feedback-form .btn:hover {
      background-color: #90b39a;
    }
    .feedback-list {
      max-width: 700px;
      margin: 2rem auto;
    }
    .feedback-item {
      background: #f7f9f8;
      padding: 1.5rem;
      margin-bottom: 1.2rem;
      border-left: 4px solid #a3cbb0;
      border-radius: 0.7rem;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
      transition: transform 0.3s ease;
    }
    .feedback-item:hover {
      transform: translateX(5px);
    }
    .feedback-item p {
      margin: 0.5rem 0;
    }
    .feedback-item small {
      color: #999;
      font-size: 0.85rem;
    }
    .rating-bars {
      margin-top: 1rem;
    }
    .rating-label {
      font-size: 0.9rem;
      margin: 0.3rem 0;
    }
    .rating-bar {
      display: flex;
      gap: 5px;
      margin-bottom: 0.5rem;
    }
    .rating-bar div {
      width: 20%;
      height: 10px;
      border-radius: 5px;
      background-color: #e0e0e0;
      position: relative;
      overflow: hidden;
    }
    .rating-bar div.filled::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #a3cbb0;
    }
    .section h2 {
      text-align: center;
      color: #2d4038;
      margin-top: 2rem;
      position: relative;
    }
    nav a {
      transition: color 0.3s ease;
    }
    nav a:hover {
      color: #a3cbb0;
    }
  </style>
  <script>
    function setupRatingBar(groupName) {
      const bars = document.querySelectorAll('.rating-bar-input[data-group="' + groupName + '"] div');
      const hiddenInput = document.querySelector('input[name="' + groupName + '"]');

      bars.forEach((bar, index) => {
        // Анимация при наведении
        bar.addEventListener('mouseenter', () => {
          bars.forEach((b, i) => {
            if (i <= index) {
              b.classList.add('hover');
            }
          });
        });

        bar.addEventListener('mouseleave', () => {
          bars.forEach(b => {
            b.classList.remove('hover');
          });
        });

        // Обработка клика
        bar.addEventListener('click', () => {
          hiddenInput.value = index + 1;
          bars.forEach((b, i) => {
            b.classList.toggle('selected', i <= index);
          });
        });
      });
    }
    document.addEventListener('DOMContentLoaded', () => {
      setupRatingBar('rating_atmosphere');
      setupRatingBar('rating_sensation');
      setupRatingBar('rating_overall');
    });
  </script>
</head>
<body>
  <header>
    <h1>Время Тишины</h1>
    <p>пространство заботы, нежности и восстановления</p>
    <nav>
      <a href="index.html">Главная</a>
      <a href="services.html">Услуги</a>
      <a href="feedback.php">Обратная связь</a>
      <a href="about.html">О кабинете</a>
    </nav>
  </header>

  <section class="section">
    <h2>Оставьте отзыв</h2>
    <form class="feedback-form" action="" method="POST">
      <label for="name">Ваше имя:</label>
      <input type="text" id="name" name="name" placeholder="Имя..." required>

      <label for="message">Ваш отзыв:</label>
      <textarea id="message" name="message" rows="5" placeholder="Поделитесь впечатлением..." required></textarea>

      <div class="rating-group">
        <label>Атмосфера:</label>
        <div class="rating-bar-input" data-group="rating_atmosphere">
          <div></div><div></div><div></div><div></div><div></div>
        </div>
        <input type="hidden" name="rating_atmosphere" value="5">
      </div>

      <div class="rating-group">
        <label>Ощущения от массажа:</label>
        <div class="rating-bar-input" data-group="rating_sensation">
          <div></div><div></div><div></div><div></div><div></div>
        </div>
        <input type="hidden" name="rating_sensation" value="5">
      </div>

      <div class="rating-group">
        <label>Общая оценка:</label>
        <div class="rating-bar-input" data-group="rating_overall">
          <div></div><div></div><div></div><div></div><div></div>
        </div>
        <input type="hidden" name="rating_overall" value="5">
      </div>

      <button type="submit" class="btn">Отправить</button>
    </form>

    <h2>Отзывы клиентов</h2>
    <div class="feedback-list">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="feedback-item">
          <p><strong><?php echo htmlspecialchars($row['name']); ?>:</strong></p>
          <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
          <div class="rating-bars">
            <div class="rating-label">Атмосфера:</div>
            <div class="rating-bar">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <div class="<?php echo ($i <= $row['rating_atmosphere']) ? 'filled' : ''; ?>"></div>
              <?php endfor; ?>
            </div>
            <div class="rating-label">Ощущения:</div>
            <div class="rating-bar">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <div class="<?php echo ($i <= $row['rating_sensation']) ? 'filled' : ''; ?>"></div>
              <?php endfor; ?>
            </div>
            <div class="rating-label">Общая оценка:</div>
            <div class="rating-bar">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <div class="<?php echo ($i <= $row['rating_overall']) ? 'filled' : ''; ?>"></div>
              <?php endfor; ?>
            </div>
          </div>
          <small><?php echo $row['created_at']; ?></small>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <footer>
    &copy; 2025 Время Тишины | Массажный кабинет
  </footer>
</body>
</html>
<?php
$conn->close();
?>