<?php
// Włącz sesję, aby sprawdzić, czy użytkownik jest zalogowany
session_start();

// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Połącz z bazą danych
$servername = "localhost"; // Zmień na swój serwer baz danych
$username = "root"; // Zmień na swoją nazwę użytkownika bazy danych
$password = ""; // Zmień na swoje hasło do bazy danych
$dbname = "trainly"; // Zmień na nazwę swojej bazy danych

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdź połączenie
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Przygotuj zapytanie SQL do pobrania list
$sql = "SELECT List_Name FROM our_lists";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $lists = [];

    // Przetwarzaj wyniki zapytania
    while ($row = $result->fetch_assoc()) {
        $lists[] = ['List_Name' => $row['List_Name']];
    }

    // Wyślij odpowiedź w formacie JSON
    echo json_encode(['lists' => $lists]);
} else {
    // Brak wyników
    echo json_encode(['lists' => []]);
}

// Zamknij połączenie z bazą danych
$conn->close();
?>
