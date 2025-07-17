<?php
$host = 'localhost';
$dbname = 'massage_cabinet';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Ошибка подключения: ' . $conn->connect_error);
}

// Получаем данные из формы
$name = $_POST['name'];
$phone = $_POST['phone'];
$telegram = $_POST['telegram'];
$service = $_POST['service'];
$date = $_POST['date'];
$time = $_POST['time'];

// Подготавливаем запрос
$stmt = $conn->prepare("INSERT INTO bookings (name, phone, telegram, service, date, time) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $phone, $telegram, $service, $date, $time);

if ($stmt->execute()) {
    echo "Запись успешно сохранена!";
} else {
    echo "Ошибка: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
