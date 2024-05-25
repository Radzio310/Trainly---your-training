<?php
  session_start();
  $id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8" />
    <title>Trainly - Zacznij trening</title>
    <link rel="icon" type="image/png" href="Graphics/Trainly-ikonka.svg" />
    <meta name="author" content="Radosław Witkowicz" />
    <meta
      name="keywords"
      content="Trainly, trening, ćwiczenia, plan treningowy, zarządzanie treningami"
    />
    <meta
      name="description"
      content="Trainly - aplikacja do zarządzania treningami"
    />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Michroma&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Lato&display=swap"
    />
    <link rel="stylesheet" href="style.css" />
    <style>
        /* HISTORY.html */
        #train_history {
        text-align: center;
        font-style: italic;
        margin-left: -2.5%;
        margin-top: 10px;
        margin-bottom: 25px;
        }

        .history-table {
        margin-left: 10%;
        margin-right: 10%;
        max-height: 57.5%;
        margin-bottom: 30px;
        overflow-y: auto;
        border: 5px double #734a40;
        }

        .table {
        width: 100%;
        border-collapse: collapse;
        }
        thead th {
            background-color: #734a40;
            color: #d9b5a0;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        tbody tr {
            color: #734a40; 
            text-align: center;
            border-radius: 50px;
            font-weight: bold;
        }
        tbody tr:hover {
            box-shadow: 0 8px 12px 0 rgba(0, 0, 0, 0.5);
            transition: 0.3s;
        }
        tbody #tr_head {
            background-color: #734a40;
            color: #d9b5a0;
            font-weight: bold;
            border-radius: 50px;
        }
        tbody #tr_head:hover {
            box-shadow: none;
        }
        td {
            text-align: center; 
            padding: 10px;
        }

        th {
            padding: 10px;
        }

        #exercise-list-training td:nth-child(1) {
            width: 40%; /* Nazwa ćwiczenia */
        }
        #exercise-list-training td:nth-child(2),
        #exercise-list-training td:nth-child(3),
        #exercise-list-training td:nth-child(4),
        #exercise-list-training td:nth-child(5) {
            width: 15%;
        }
    </style>
  </head>

  <body class="fade-in">
    <header>
      <div class="container">
        <img class="logo" src="Graphics/Trainly-logo-active.png" />
      </div>
      <nav>
        <div class="hamburger">
          <div class="line"></div>
          <div class="line"></div>
          <div class="line"></div>
        </div>
        <div class="menu">
          <ul>
            <a href="userpage.php">
              <li>Twój panel</li>
            </a>
            <a href="settings.php">
              <li>Ustawienia</li>
            </a>
            <a href="user_contact.html">
              <li>Kontakt</li>
            </a>
            <a href="index.html">
              <li>Wyloguj</li>
            </a>
          </ul>
        </div>
      </nav>
    </header>
    <main class="history_mob" style="height: 450px;">
        <!-- Kod dla historii treningów -->
        <h2 id="train_history">HISTORIA TRENINGÓW</h2>
        <div class="history-table">
            <table class="table">
                <thead>
                    <tr id="tr_head">
                        <th>Nr treningu</th>
                        <th>Data treningu</th>
                        <th>Czas treningu</th>
                        <th>Ćwiczenia</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Pętla generująca wiersze tabeli -->
                    <?php

                        require_once "config.php";

                        // Stwórz połączenie z bazą danych
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        $conn->set_charset("utf8mb4");

                        // Sprawdzenie połączenia
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Zapytanie SQL pobierające historię treningów dla danego użytkownika (możesz dostosować ten kod do pobierania danych dla konkretnego użytkownika)
                        $historyQuery = "SELECT * FROM history WHERE User_ID = '$id'";
                        $historyResult = $conn->query($historyQuery);
                        $licznik = 1;

                        if ($historyResult->num_rows > 0) {
                            // Jeśli są dostępne dane, generujemy wiersze tabeli
                            while ($row = $historyResult->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $licznik++ . "</td>";
                                echo "<td>" . $row['Date'] . "</td>";
                                echo "<td>" . $row['Training_time'] . "</td>";
                                echo "<td><button class='button' onclick='openModal(" . $row['Training_ID'] . ")'>Zobacz raport</button></td>";
                                echo "</tr>";
                            }                            
                        } else {
                            echo "<tr><td colspan='4'>Brak danych do wyświetlenia.</td></tr>";
                        }

                        // Zamknięcie połączenia
                        $conn->close();
                        ?>

                </tbody>
            </table>
        </div>
        <a href="training.html" class="button" id="train_return" 
        style='
        font-family: "Michroma", sans-serif;
        font-weight: 900;
        font-style: italic;
        text-align: center;
        width: 40%;
        color: #734a40;
        border: 3px solid #734a40;
        background-color: transparent;
        margin-left: 35%;
        transition: background-color 0.5s, color 0.3s; /* Dodaj przejścia */
        '
        onmouseover="this.style.backgroundColor='#734a40'; this.style.color='#d9b5a0';"
        onmouseout="this.style.backgroundColor='transparent'; this.style.color='#734a40';"
        '>Powrót do treningów</a>

        <!-- Modal dla raportu treningu -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Raport treningu</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Ćwiczenie</th>
                            <th>Obciąż.</th>
                            <th>Powtórz.</th>
                            <th>Serie</th>
                            <th>✓</th>
                        </tr>
                    </thead>
                    <tbody id="exercise-list-training">

                    </tbody>

                </table>
            </div>
        </div>
    </main>

    <footer>
      <a href="user_homepage.html"
        ><img class="logo" src="Graphics/Trainly-logo_2.png"
      /></a>
      <div class="social-media-icons">
        <a
          href="https://www.facebook.com/profile.php?id=61558575186327"
          target="_blank"
        >
          <img class="icon" src="Graphics/facebook-icon.png" alt="Facebook" />
        </a>
        <a
          href="https://www.instagram.com/trainly_yourtraining/"
          target="_blank"
        >
          <img class="icon" src="Graphics/instagram-icon.png" alt="Instagram" />
        </a>
      </div>
    </footer>
    <script>
        // Funkcja otwierająca modal
        function openModal(trainingId) {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";

            // Wyczyść poprzednie dane ćwiczeń
            document.getElementById("exercise-list-training").innerHTML = "";

            // Wykonaj AJAX, aby pobrać dane ćwiczeń dla wybranego Training_ID
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "get_exercises.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    var exerciseList = document.getElementById("exercise-list-training");
                    if (response.length > 0) {
                        response.forEach(function (exercise) {
                            var row = document.createElement("tr");
                            var completedClass = exercise.Czy_wykonane ? 'completed' : 'not-completed';
                            var statusText = exercise.Czy_wykonane ? '✓' : '✗';
                            row.innerHTML = `
                                <td style='width: 50%;'>${exercise.Nazwa_cwiczenia}</td>
                                <td>${exercise.Obciazenie}kg</td>
                                <td>${exercise.Powtorzenia}</td>
                                <td>${exercise.Serie}</td>
                                <td class="${completedClass}">${statusText}</td>
                            `;
                            exerciseList.appendChild(row);
                        });
                    } else {
                        var row = document.createElement("tr");
                        row.innerHTML = "<td colspan='5'>Brak danych do wyświetlenia.</td>";
                        exerciseList.appendChild(row);
                    }
                }
            };
            xhr.send("trainingId=" + trainingId);
        }

        // Funkcja zamykająca modal
        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }
    </script>
  </body>
</html>
