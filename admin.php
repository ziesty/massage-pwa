<?php
// admin.php
$host = 'localhost';
$dbname = 'massage_cabinet';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Ошибка подключения: ' . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Админ-панель | Время Тишины</title>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 2rem;
    }
    h1 {
      text-align: center;
      color: #4f5d56;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2rem;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #e6efea;
      color: #2d4038;
    }
    tr:hover {
      background-color: #f1f9f6;
    }
  </style>
</head>
<body>
  <h1>Заявки клиентов</h1>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Имя</th>
        <th>Телефон</th>
        <th>Telegram</th>
        <th>Услуга</th>
        <th>Дата</th>
        <th>Время</th>
        <th>Создано</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['phone']); ?></td>
          <td><?php echo htmlspecialchars($row['telegram']); ?></td>
          <td><?php echo htmlspecialchars($row['service']); ?></td>
          <td><?php echo $row['date']; ?></td>
          <td><?php echo $row['time']; ?></td>
          <td><?php echo $row['created_at']; ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
<?php
$conn->close();
?>
