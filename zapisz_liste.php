<?php
session_start();
$id = $_SESSION['user_id']; // ID użytkownika, możesz go odczytać z sesji

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Odbierz dane z żądania POST
  $postData = json_decode(file_get_contents("php://input"));

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

  // Odbierz nazwę listy i ćwiczenia
  $listName = $postData->listName;
  $exercises = $postData->exercises; // Przekształć tablicę w format JSON

  // Przygotuj zapytanie SQL
  $sql = "INSERT INTO your_lists (User_ID, List_Name, Exercise_list) VALUES ('$id', '$listName', '$exercises')";

  // Wykonaj zapytanie
  if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
    //header("Location: your_lists.html?addList=true&listName=$listName");
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  // Zamknij połączenie z bazą danych
  $conn->close();
}
?>
