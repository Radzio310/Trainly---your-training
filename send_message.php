<?php
session_start();

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
    //$attachment = $_FILES['attachment']['name'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    $date = date("d.m.Y H:i:s"); // Format: DD.MM.RRRR GG:MM:SS 

    // Zapisz dane do bazy danych
    $sql = "INSERT INTO guest_messages (Name, Email, Phone, Message, Date) VALUES ('$name', '$email', '$phone', '$message', '$date')";

    if ($conn->query($sql) === TRUE) {
        // Sukces - wyświetl komunikat
        header("Location: contact.html?success=true");
        exit();
    } else {
        // Błąd - wyświetl komunikat
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>