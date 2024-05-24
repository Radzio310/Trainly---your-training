<?php
require_once "config.php";

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdź połączenie
if ($conn->connect_error) {
  die("Błąd połączenia: " . $conn->connect_error);
}

// Przetwórz dane formularza
$name = $_POST['name'];
$surname = $_POST['surname'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'];

// Sprawdź, czy email nie jest już wykorzystywany
$sql_check_email = "SELECT * FROM users WHERE Login='$email'";
$result_check_email = $conn->query($sql_check_email);
if ($result_check_email->num_rows > 0) {
  // Email jest już w użyciu, przekieruj na stronę rejestracji z komunikatem o błędzie
  header("Location: register.html?success=false&error=email_in_use");
  exit(); // Zakończ skrypt, aby uniknąć wykonywania kolejnych instrukcji
}

// Wykonaj zapytanie SQL
$sql = "INSERT INTO users (Name, Surname, Age, Gender, Phone, Login, Password) VALUES ('$name', '$surname', $age, '$gender', '$phone', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
  // Dane zostały dodane poprawnie
  header("Location: register.html?success=true");
} else {
  header("Location: register.html?success=false&error=database_error");
}

$conn->close();
?>
