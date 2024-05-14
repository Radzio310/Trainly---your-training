<?php
session_start();
$id = $_SESSION['user_id'];
// Sprawdź, czy dane zostały przesłane za pomocą metody POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sprawdź, czy wszystkie wymagane dane zostały przesłane
    if (isset($_POST['description']) && isset($_POST['time']) && isset($_POST['dayId'])) {
        // Pobierz dane z formularza
        $description = $_POST['description'];
        $time = $_POST['time'];
        $dayId = $_POST['dayId'];

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

        // Przygotuj i wykonaj zapytanie SQL, np. wstaw dane do tabeli
        $sql = "INSERT INTO calendars (User_ID, Training_description, Training_time, Training_day) VALUES ('$id','$description', '$time', '$dayId')";

        if ($conn->query($sql) === TRUE) {
            // Jeśli zapytanie zostało wykonane pomyślnie, zwróć sukces
            echo "success";
        } else {
            // Jeśli wystąpił błąd podczas wykonywania zapytania, zwróć błąd
            echo "error: " . $conn->error;
        }

        // Zamknij połączenie z bazą danych
        $conn->close();
    } else {
        // Jeśli brakuje danych, zwróć odpowiedni komunikat błędu
        echo "error: Brakuje wymaganych danych";
    }
} else {
    // Jeśli dane nie zostały przesłane za pomocą metody POST, zwróć odpowiedni komunikat błędu
    echo "error: Nieprawidłowe żądanie";
}
?>
