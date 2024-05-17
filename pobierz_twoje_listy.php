<?php
// Pobierz parametr listType z adresu URL
$listType = $_GET['listType'];

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

// Zapytanie SQL do pobrania listy ćwiczeń dla danego rodzaju treningu
$sql = "SELECT Exercise_list FROM your_lists WHERE List_name = '$listType'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $exercises = json_decode($row["Exercise_list"], true);

    // Zwróć listę ćwiczeń jako odpowiedź JSON
    echo json_encode(array("exercises" => $exercises));
} else {
    echo json_encode(array("exercises" => array())); // Jeśli lista ćwiczeń jest pusta
}

$conn->close();
?>
