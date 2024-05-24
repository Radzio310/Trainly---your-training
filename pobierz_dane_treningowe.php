<?php
session_start();
$id = $_SESSION['user_id'];

require_once "config.php";

// Stwórz połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdź połączenie
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Zapytanie SQL do pobrania danych treningowych z kalendarza użytkownika
$sql = "SELECT Training_day, Training_description, Training_time FROM calendars WHERE User_ID = '$id'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $plannedDays = array();
    // Pętla przez wyniki zapytania
    while ($row = $result->fetch_assoc()) {
        // Dodaj dane dnia treningowego do tablicy
        $plannedDays[] = array(
            "Training_day" => $row["Training_day"],
            "Training_description" => $row["Training_description"],
            "Training_time" => $row["Training_time"]
        );
    }
    // Zwróć dane w formacie JSON
    echo json_encode(array("success" => true, "plannedDays" => $plannedDays));
} else {
    // Jeśli brak danych, zwróć informację o błędzie
    echo json_encode(array("success" => false));
}

// Zamknij połączenie z bazą danych
$conn->close();
?>
