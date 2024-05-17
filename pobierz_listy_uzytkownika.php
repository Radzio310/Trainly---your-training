<?php
session_start();
$id = $_SESSION['user_id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trainly";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT List_Name FROM your_lists WHERE User_ID = $id";
$result = $conn->query($sql);

$lists = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $lists[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode(array('lists' => $lists));
?>
