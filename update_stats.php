<?php
session_start();
$id = $_SESSION['user_id'];

require_once "config.php";

// Stwórz połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pobierz dane JSON z żądania POST
$data = json_decode(file_get_contents("php://input"), true);
$trainingTimeInSeconds = $data['Training_time']; // Czas treningu w sekundach

// Przeliczenie kalorii na podstawie przyjętego przelicznika 6 kalorii na minutę
$caloriesBurned = ($trainingTimeInSeconds / 60) * 6; // 6 kalorii na minutę

// Pobierz aktualne statystyki użytkownika
$statsQuery = "SELECT * FROM stats WHERE User_ID = '$id'";
$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

if ($stats) {
    // Przelicz istniejący średni czas treningu z HH:MM:SS na sekundy
    list($h, $m, $s) = explode(':', $stats['Training_time']);
    $currentAverageTrainingTimeInSeconds = ($h * 3600) + ($m * 60) + $s;
    $currentTrainingCount = $stats['Training_count'];

    // Przelicz całkowity czas treningu w sekundach
    $currentTotalTrainingTimeInSeconds = $currentAverageTrainingTimeInSeconds * $currentTrainingCount;

    // Nowe statystyki
    $newTrainingCount = $currentTrainingCount + 1;
    $newTotalTrainingTimeInSeconds = $currentTotalTrainingTimeInSeconds + $trainingTimeInSeconds;
    $newAverageTrainingTimeInSeconds = $newTotalTrainingTimeInSeconds / $newTrainingCount;

    // Przelicz średni czas treningu z powrotem na HH:MM:SS
    $newHours = floor($newAverageTrainingTimeInSeconds / 3600);
    $newMinutes = floor(($newAverageTrainingTimeInSeconds % 3600) / 60);
    $newSeconds = floor($newAverageTrainingTimeInSeconds % 60);
    $newAverageTrainingTime = sprintf('%02d:%02d:%02d', $newHours, $newMinutes, $newSeconds);

    $newTotalCalories = $stats['Calories'] + $caloriesBurned;

    // Określenie intensywności
    $intensity = '';
    if ($newAverageTrainingTimeInSeconds < 900) { // 15 minut * 60 sekund
        $intensity = 'bardzo niska';
    } elseif ($newAverageTrainingTimeInSeconds < 1800) { // 30 minut * 60 sekund
        $intensity = 'niska';
    } elseif ($newAverageTrainingTimeInSeconds < 300) { // 50 minut * 60 sekund
        $intensity = 'średnia';
    } elseif ($newAverageTrainingTimeInSeconds < 5400) { // 90 minut * 60 sekund
        $intensity = 'wysoka';
    } else {
        $intensity = 'bardzo wysoka';
    }

    $updateStatsSql = "UPDATE stats SET
        Training_count = '$newTrainingCount',
        Calories = '$newTotalCalories',
        Training_time = '$newAverageTrainingTime',
        Intensity = '$intensity'
        WHERE User_ID = '$id'";
    mysqli_query($conn, $updateStatsSql);
} else {
    // Dodanie nowych statystyk, jeśli nie istnieją
    $intensity = '';
    if ($trainingTimeInSeconds < 1200) { // 20 minut * 60 sekund
        $intensity = 'bardzo niska';
    } elseif ($trainingTimeInSeconds < 2100) { // 35 minut * 60 sekund
        $intensity = 'niska';
    } elseif ($trainingTimeInSeconds < 4500) { // 75 minut * 60 sekund
        $intensity = 'średnia';
    } elseif ($trainingTimeInSeconds < 6000) { // 100 minut * 60 sekund
        $intensity = 'wysoka';
    } else {
        $intensity = 'bardzo wysoka';
    }
    
    $newAverageTrainingTime = sprintf('%02d:%02d:%02d', floor($trainingTimeInSeconds / 3600), floor(($trainingTimeInSeconds % 3600) / 60), floor($trainingTimeInSeconds % 60));

    $insertStatsSql = "INSERT INTO stats (User_ID, Training_time, Training_count, Calories, Intensity)
    VALUES ('$id', '$newAverageTrainingTime', 1, '$caloriesBurned', '$intensity')";
    mysqli_query($conn, $insertStatsSql);

    // Ustawienie wartości dla alertu, gdy nie ma wcześniejszych danych
    //$currentAverageTrainingTime = '00:00:00';
}

$conn->close();
?>
