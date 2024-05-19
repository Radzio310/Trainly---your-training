<?php

session_start();
$id = $_SESSION['user_id'];

// Połącz się z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trainly";

// Stwórz połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

$date = $_GET['date'];

$sql = "SELECT * FROM history WHERE User_ID = '$id' AND DATE(Date) = '$date'";
$result = mysqli_query($conn, $sql);

$response = ['success' => false];

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $response['success'] = true;
    $response['report'] = [
        'Date' => $row['Date'],
        'Training_time' => $row['Training_time'],
        'Exercises' => json_decode($row['Exercises'], true)
    ];
}

echo json_encode($response);

mysqli_close($conn);
?>
