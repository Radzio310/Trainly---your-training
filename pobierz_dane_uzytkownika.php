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

// Zapytanie SQL do pobrania danych użytkownika
$sql = "SELECT Gender FROM users WHERE User_ID = '$id'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    // Zwróć dane w formacie JSON
    echo json_encode(array("success" => true, "Gender" => $userData['Gender']));
} else {
    // Jeśli brak danych, zwróć informację o błędzie
    echo json_encode(array("success" => false));
}

// Zamknij połączenie z bazą danych
$conn->close();
?>
