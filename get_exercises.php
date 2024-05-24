<?php
session_start();
require_once "config.php";

if (isset($_POST['trainingId'])) {
    $trainingId = $_POST['trainingId'];

    // Stwórz połączenie z bazą danych
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");

    // Sprawdzenie połączenia
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Pobierz dane ćwiczeń dla danego treningu z bazy danych
    $exerciseQuery = "SELECT Exercises FROM history WHERE Training_ID = '$trainingId'";
    $exerciseResult = $conn->query($exerciseQuery);

    if ($exerciseResult->num_rows > 0) {
        // Jeśli są dostępne dane, pobierz listę ćwiczeń
        $exerciseData = $exerciseResult->fetch_assoc();
        $exerciseList = json_decode($exerciseData['Exercises'], true);
        echo json_encode($exerciseList);
    } else {
        echo json_encode([]);
    }

    // Zamknięcie połączenia
    $conn->close();
} else {
    echo json_encode([]);
}
?>
