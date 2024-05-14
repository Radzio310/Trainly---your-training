<?php
session_start();
$id = $_SESSION['user_id'];

// Połącz z bazą danych
$servername = "localhost";
$username = "root"; // domyślnie 'root' w XAMPP
$password = ""; // domyślne hasło w XAMPP jest puste
$dbname = "trainly";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Sprawdź, czy formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz dane z formularza
    $attachment = $_FILES['attachment']['name'];
    $message = $_POST['message'];
    $date = date("d.m.Y H:i:s"); // Format: DD.MM.RRRR GG:MM:SS 

    // Zapisz dane do bazy danych
    $sql = "INSERT INTO messages (User_ID, Date, Message) VALUES ('$id', '$date' , '$message')";

    if ($conn->query($sql) === TRUE) {
        // Sukces - wyświetl komunikat
        header("Location: user_contact.php?success=true");
        exit();
    } else {
        // Błąd - wyświetl komunikat
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>