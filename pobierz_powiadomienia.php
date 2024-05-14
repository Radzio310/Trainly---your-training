<?php
session_start();
$id = $_SESSION['user_id'];

// Połącz się z bazą danych
$servername = "localhost";
$username = "root"; // domyślnie 'root' w XAMPP
$password = ""; // domyślne hasło w XAMPP jest puste
$dbname = "trainly";

// Stwórz połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdź połączenie
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Zapytanie SQL do pobrania danych powiadomień użytkownika
$sql = "SELECT notifications.Title, notifications.Content FROM notifications WHERE notifications.User_ID = '$id' OR notifications.User_ID = '0' ORDER BY notifications.Notification_ID DESC";

$result = $conn->query($sql);

// Utwórz pustą tablicę na powiadomienia
$notifications = array();

if ($result->num_rows > 0) {
    // Pętla przez wyniki zapytania
    while ($row = $result->fetch_assoc()) {
        // Dodaj tytuł i treść powiadomienia do tablicy
        $notifications[] = array(
            "Title" => $row["Title"],
            "Content" => $row["Content"]
        );
    }
}

// Zamknij połączenie z bazą danych
$conn->close();

// Zwróć dane w formacie JSON
echo json_encode($notifications);
?>
