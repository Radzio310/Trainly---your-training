<?php
session_start();

// Pobierz nazwę ćwiczenia z parametru GET
$exerciseName = $_GET['exerciseName'];

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

// Zapytanie SQL do pobrania opisu ćwiczenia
$sql = "SELECT Description FROM exercises WHERE Name = '$exerciseName'";
$result = $conn->query($sql);

$response = array("description" => "");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response["description"] = $row["Description"];
}

// Zwróć opis ćwiczenia jako odpowiedź JSON
echo json_encode($response);

$conn->close();
?>
