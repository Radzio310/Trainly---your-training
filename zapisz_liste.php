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

 // Sprawdź, czy istnieje już lista o danej nazwie dla danego użytkownika
    $sql_check = "SELECT * FROM your_lists WHERE User_ID='$id' AND List_Name='$listName'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // Lista o danej nazwie już istnieje
        echo json_encode(array("error" => "listExists"));
        header("Location: create_list.html?error=listExists");

    } else {
        // Przygotuj zapytanie SQL do dodania nowej listy
        $sql_insert = "INSERT INTO your_lists (User_ID, List_Name, Exercise_list) VALUES ('$id', '$listName', '$exercises')";

        // Wykonaj zapytanie
        if ($conn->query($sql_insert) === TRUE) {
            header("Location: your_lists.html?addList=true&listName=$listName");
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }
  // Zamknij połączenie z bazą danych
  $conn->close();
}
?>
