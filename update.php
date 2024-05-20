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

// UPDATE DATABASE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['name'];
    $user_surname = $_POST['surname'];
    $user_age = $_POST['age'];
    $user_phone = $_POST['phone'];
    $user_password = $_POST['password'];

    // Pobierz obecne hasło użytkownika
    $sql_current_password = "SELECT Password FROM users WHERE User_ID='$id'";
    $result = $conn->query($sql_current_password);
    $row = $result->fetch_assoc();
    $current_password = $row['Password'];

    // Sprawdź czy nowe hasło jest różne od obecnego
    if ($user_password != $current_password) {
        // Tutaj możesz zaktualizować dane użytkownika w bazie danych
        if ($user_password == "") {
            $sql = "UPDATE users SET Name='$user_name', Surname='$user_surname', Age='$user_age', Phone='$user_phone' WHERE User_ID='$id'";
        } else {
            $sql = "UPDATE users SET Name='$user_name', Surname='$user_surname', Age='$user_age', Phone='$user_phone', Password='$user_password' WHERE User_ID='$id'";
        }

        if ($conn->query($sql) === TRUE) {
            // Zapisano zmiany w bazie danych
            header("Location: settings.php?success=true");
        } else {
            // Błąd zapisu w bazie danych
            header("Location: settings.php?success=false");
        }
    } else {
        // Nowe hasło jest takie same jak obecne
        header("Location: settings.php?error=same_password");
    }
}

?>
