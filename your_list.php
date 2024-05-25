<?php
  session_start();
  $id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8" />
    <title>Trainly - Listy ćwiczeń</title>
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
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.9.0/main.min.css"
      rel="stylesheet"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.9.0/main.min.js"></script>
  </head>

  <body class="fade-in">
    <div id="overlay_notification">
      <div id="notification-modal">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2 id="notification-title"></h2>
        <p id="notification-content"></p>
      </div>
    </div>
    <header>
      <div class="container">
        <a href="training.html">
          <img class="logo" src="Graphics/Trainly-logo.png" />
        </a>
      </div>
      <nav>
        <div class="hamburger">
          <div class="line"></div>
          <div class="line"></div>
          <div class="line"></div>
        </div>
        <div class="menu">
          <ul>
            <a href="userpage.php?show_lists=true">
              <li id="konto">Twój panel</li>
            </a>
            <a href="settings.php">
              <li id="konto">Ustawienia</li>
            </a>
            <a href="user_contact.php">
              <li id="konto">Kontakt</li>
            </a>
            <a href="index.html">
              <li id="konto">Wyloguj</li>
            </a>
          </ul>
        </div>
      </nav>
    </header>
    <main class="user_mob userpage">
      <div class="sidebar">
        <ul>
          <li id="konto">
            <img
              src="Graphics\Panel.png"
              alt="Twój panel"
              onclick="sidebar('show_panel')"
              id="panel"
            />
          </li>
          <li id="konto">
            <img
              src="Graphics\Kalendarz.png"
              alt="Kalendarz"
              onclick="sidebar('show_calendar')"
              id="kalendarz"
            />
          </li>
          <li id="konto">
            <img
              src="Graphics\Powiadomienia.png"
              alt="Historia i powiadomienia"
              onclick="sidebar('show_notes')"
              id="powiadomienia"
            />
          </li>
          <li id="konto">
            <img
              src="Graphics\Statystyki.png"
              alt="Statystyki"
              onclick="sidebar('show_stats')"
              id="statystyki"
            />
          </li>
          <li id="konto">
            <img
              src="Graphics\Lista.png"
              alt="Lista ćwiczeń"
              onclick="sidebar('show_lists')"
              id="lista"
              class="selected"
            />
          </li>
        </ul>
      </div>
      <div class="Twoje">
        <h2 id="twoje_listy_h2">Twoje listy ćwiczeń</h2>
        <div class="Widok_list">
          <ul class="twoje-listy">
            <?php
              require_once "config.php";
              // Połącz się z bazą danych
              $conn = new mysqli($servername, $username, $password, $dbname);
        
              // Sprawdź połączenie
              if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
              }
        
              // Zapytanie SQL do pobrania nazw wszystkich list ćwiczeń
              $sql = "SELECT List_Name FROM your_lists WHERE User_ID = $id";
              $result = $conn->query($sql);
        
              // Wyświetl nazwy list ćwiczeń jako elementy <li>
              if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                      echo '<li onclick="openModal(\'' . $row["List_Name"] . '\')">';
                      echo '<h3>"' . $row["List_Name"] . '"</h3>';
                      echo '</li>';
                  }
              }
        
              $conn->close();
            ?>
          </ul>
        </div>
        <a class="button" href="create_list.html"> Stwórz nową listę </a>
      </div>
      <div id="myModal" class="modal">
        <div class="modal-content">
          <span class="close" onclick="closeModal()">&times;</span>
          <ul id="exercise-list"></ul>
          <button class="button" onclick="startTraining(listName)">
            Rozpocznij trening
          </button>
        </div>
      </div>
      <div class="success_notification" id="success_notification">
        <span id="success_message"></span>
      </div>
    </main>
    <footer>
      <a href="user_homepage.html">
        <img class="logo" src="Graphics/Trainly-logo_2.png" />
      </a>
      <!-- Dodaj ikonki mediów społecznościowych -->
      <div class="social-media-icons">
        <a
          href="https://www.facebook.com/profile.php?id=61558575186327"
          target="_blank"
          ><img class="icon" src="Graphics/facebook-icon.png" alt="Facebook"
        /></a>
        <a
          href="https://www.instagram.com/trainly_yourtraining/"
          target="_blank"
          ><img class="icon" src="Graphics/instagram-icon.png" alt="Instagram"
        /></a>
      </div>
    </footer>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Sprawdź czy parametr "add_list" jest obecny w adresie URL
        var urlParams = new URLSearchParams(window.location.search);
        var addList = urlParams.get("add_list");
        if (addList === "true") {
          var listName = urlParams.get("list_name");
          showSuccessNotification(
            "Lista " + listName + " została dodana do Twoich list!"
          );
        }
      });

      document.addEventListener("DOMContentLoaded", function () {
        const hamburger = document.querySelector(".hamburger");
        const menu = document.querySelector(".menu");

        hamburger.addEventListener("click", function () {
          this.classList.toggle("clicked");
          menu.classList.toggle("active");
        });
      });

      var listName = "";

      var exerciseList = document.getElementById("exercise-list");

      function openModal(listType) {
        var modal = document.getElementById("myModal");

        // Czyść listę ćwiczeń
        exerciseList.innerHTML = "";

        // Pobierz listę ćwiczeń dla danego rodzaju treningu z bazy danych
        fetchExerciseList(listType);

        modal.style.display = "block";
        listName = listType; // Ustaw nazwę listy
      }



      function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
      }

      function startTraining(listType) {
        window.location.href = "TRAIN.html?exerciseList=" + listType;
      }

      // Funkcja do pobierania listy ćwiczeń dla danego rodzaju treningu z bazy danych
      function fetchExerciseList(listType) {
        // Wysyłamy zapytanie AJAX do skryptu PHP, który pobierze listę ćwiczeń
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "pobierz_twoje_listy.php?listType=" + listType, true);
        xhr.onload = function () {
          if (xhr.status === 200) {
            // Odpowiedź z serwera
            var data = JSON.parse(xhr.responseText);
            if (data && data.exercises) {
              // Wyświetl listę ćwiczeń w modalu
              data.exercises.forEach(function (exercise) {
                var li = document.createElement("li");
                li.textContent = exercise;
                exerciseList.appendChild(li);
              });
            }
          } else {
            alert("Wystąpił błąd podczas pobierania listy ćwiczeń.");
          }
        };
        xhr.send();
      }

      // Funkcja wyświetlająca powiadomienie o sukcesie
      function showSuccessNotification(message) {
        var successNotification = document.getElementById(
          "success_notification"
        );
        var successMessage = document.getElementById("success_message");
        successMessage.innerText = message; // Ustawiamy treść powiadomienia

        successNotification.style.display = "block"; // Wyświetlamy powiadomienie

        // Ukrywamy powiadomienie po 3 sekundach
        setTimeout(function () {
          successNotification.style.display = "none";
        }, 3000);
      }

      function sidebar(where) {
        window.location.href = "userpage.php?" + where + "=true";
      }
    </script>
  </body>
</html>
