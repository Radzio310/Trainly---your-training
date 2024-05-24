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

$trainingTime = $_GET['time'];

// Przeliczenie kalorii na podstawie przyjętego przelicznika 6 kalorii na minutę
$timeParts = explode(' ', $trainingTime);
$hours = (int)str_replace('h', '', $timeParts[0]);
$minutes = (int)str_replace('m', '', $timeParts[1]);
$seconds = (int)str_replace('s', '', $timeParts[2]);
$totalMinutes = ($hours * 60) + $minutes + ($seconds / 60);
$totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
$caloriesBurned = $totalMinutes * 6; // 6 kalorii na minutę

// Pobierz aktualne statystyki użytkownika
$statsQuery = "SELECT * FROM stats WHERE User_ID = '$id'";
$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

if ($stats) {
    // Przelicz istniejący czas treningu z HH:MM:SS na sekundy
    list($h, $m, $s) = explode(':', $stats['Training_time']);
    $currentTrainingTimeInSeconds = ($h * 3600) + ($m * 60) + $s;
    $newTrainingCount = $stats['Training_count'] + 1;
    $newTotalTrainingTimeInSeconds = $currentTrainingTimeInSeconds + $totalSeconds;
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
    } elseif ($newAverageTrainingTimeInSeconds < 1500) { // 25 minut * 60 sekund
        $intensity = 'niska';
    } elseif ($newAverageTrainingTimeInSeconds < 2700) { // 45 minut * 60 sekund
        $intensity = 'średnia';
    } elseif ($newAverageTrainingTimeInSeconds < 4500) { // 75 minut * 60 sekund
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
    if ($totalSeconds < 1200) { // 20 minut * 60 sekund
        $intensity = 'bardzo niska';
    } elseif ($totalSeconds < 2100) { // 35 minut * 60 sekund
        $intensity = 'niska';
    } elseif ($totalSeconds < 4500) { // 75 minut * 60 sekund
        $intensity = 'średnia';
    } elseif ($totalSeconds < 6000) { // 100 minut * 60 sekund
        $intensity = 'wysoka';
    } else {
        $intensity = 'bardzo wysoka';
    }

    $initialTrainingTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

    $insertStatsSql = "INSERT INTO stats (User_ID, Training_time, Training_count, Calories, Intensity)
    VALUES ('$id', '$initialTrainingTime', 1, '$caloriesBurned', '$intensity')";
    mysqli_query($conn, $insertStatsSql);
}

header("Location: userpage.php?train=training_saved");

// Zamknięcie połączenia
$conn->close();
?>
