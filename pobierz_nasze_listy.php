<?php
header('Content-Type: application/json');

require_once "config.php";

// Stwórz połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdź połączenie
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Zapytanie SQL do pobrania list ćwiczeń
$sql = "SELECT List_name, Exercise_list FROM our_lists";

$result = $conn->query($sql);
$lists = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $exercise_list = json_decode($row["Exercise_list"], true);
        $lists[] = array(
            "List_name" => $row["List_name"],
            "Exercise_list" => $exercise_list,
            "Exercise_count" => count($exercise_list)
        );
    }
    echo json_encode(array("lists" => $lists));
} else {
    echo json_encode(array("lists" => array()));
}

$conn->close();
?>
