<?php

session_start();
$id = $_SESSION['user_id'];

// Połącz się z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trainly";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$month = $_GET['month'];
$year = $_GET['year'];

$sql = "SELECT DAY(Date) as day FROM history WHERE MONTH(Date) = $month AND YEAR(Date) = $year";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $month, $year);
$stmt->execute();
$result = $stmt->get_result();

$days = array();
while ($row = $result->fetch_assoc()) {
    $days[] = $row['day'];
}

$stmt->close();
$conn->close();

echo json_encode($days);
?>
