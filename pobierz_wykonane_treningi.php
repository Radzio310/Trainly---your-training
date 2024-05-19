<?php
session_start();
$id = $_SESSION['user_id'];

// Połącz się z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trainly";

// Stwórz połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pobranie dni wykonanych treningów dla zalogowanego użytkownika
$sql = "SELECT DISTINCT DATE_FORMAT(Date, '%e') AS day FROM history WHERE User_ID = ? ORDER BY Date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$trainingDays = array();
while ($row = $result->fetch_assoc()) {
    $trainingDays[] = $row['day'];
}

$response = array(
    'success' => true,
    'trainingDays' => $trainingDays
);

echo json_encode($response);

// Zamknięcie połączenia
$conn->close();
?>
