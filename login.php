<?php
// Dane dostępowe do bazy danych MySQL w XAMPP
require_once "config.php";

// Tworzymy połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzamy, czy udało się połączyć z bazą danych
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Pobieramy dane logowania z formularza
$login = $_POST['login'];
$password = $_POST['password'];

// Zabezpieczamy przed atakami SQL Injection
$login = mysqli_real_escape_string($conn, $login);
$password = mysqli_real_escape_string($conn, $password);

// Tworzymy zapytanie SQL do sprawdzenia danych logowania
$sql = "SELECT * FROM users WHERE login='$login' AND password='$password'";
$result = $conn->query($sql);

// Sprawdzamy, czy zapytanie zwróciło jakieś wyniki
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['User_ID'];

    // Rozpoczynamy sesję, aby przechować ID użytkownika
    session_start();
    $_SESSION['user_id'] = $user_id;

    // Przekierowanie użytkownika na stronę główną po udanym logowaniu z przekazaniem ID użytkownika
    header("Location: userpage.php");
    exit();
} else {
    // Błędne dane logowania, przekierowanie użytkownika na stronę logowania z komunikatem o błędzie
    header("Location: login.html?error=true");
    exit();
}

// Zamykamy połączenie z bazą danych
$conn->close();
?>