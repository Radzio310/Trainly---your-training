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

// Zapytanie SQL do pobrania danych statystycznych treningów użytkownika
$sql = "SELECT Training_count, Training_time, Calories, Intensity FROM stats WHERE User_ID = '$id'";

$result = $conn->query($sql);

// Utwórz tablicę na dane statystyczne
$statistics = array();

if ($result->num_rows > 0) {
    // Pobierz wiersz wynikowy jako tablicę asocjacyjną
    $row = $result->fetch_assoc();
    // Dodaj dane do tablicy
    $statistics = array(
        "Training_count" => $row["Training_count"],
        "Training_time" => $row["Training_time"],
        "Calories" => $row["Calories"],
        "Intensity" => $row["Intensity"]
    );
}
else {
    $statistics = array(
        "Training_count" => 0,
        "Training_time" => 0,
        "Calories" => 0,
        "Intensity" => "Brak danych"
    );
}

// Zamknij połączenie z bazą danych
$conn->close();

// Zwróć dane w formacie JSON
echo json_encode($statistics);
?>
