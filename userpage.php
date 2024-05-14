<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8" />
    <title>Trainly - Twój panel</title>
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
            <a href="userpage.php" class="current_site">
              <li>Twój panel</li>
            </a>
            <a href="settings.php">
              <li>Ustawienia</li>
            </a>
            <a href="user_contact.php">
              <li>Kontakt</li>
            </a>
            <a href="index.html">
              <li>Wyloguj</li>
            </a>
          </ul>
        </div>
      </nav>
    </header>
    <main class="user_mob userpage">
      <div class="sidebar">
        <ul>
          <li>
            <img
              src="Graphics\Panel.png"
              alt="Twój panel"
              onclick="showContent('panel')"
              id="panel"
              class="selected"
            />
          </li>
          <li>
            <img
              src="Graphics\Kalendarz.png"
              alt="Kalendarz"
              onclick="showContent('kalendarz'); markPlannedDays()"
              id="kalendarz"
            />
          </li>
          <li>
            <img
              src="Graphics\Powiadomienia.png"
              alt="Historia i powiadomienia"
              onclick="showContent('powiadomienia')"
              id="powiadomienia"
            />
          </li>
          <li>
            <img
              src="Graphics\Statystyki.png"
              alt="Statystyki"
              onclick="showContent('statystyki')"
              id="statystyki"
            />
          </li>
          <li>
            <img
              src="Graphics\Lista.png"
              alt="Lista ćwiczeń"
              onclick="showContent('lista')"
              id="lista"
            />
          </li>
        </ul>
      </div>
      <section class="content" id="content">
        <!-- Sekcja na ekranie głównym -->
        <div id="userinfo" class="userinfo">
          <br />
          <?php
              session_start();
              $id = $_SESSION['user_id'];

              $servername = "localhost";
              $username = "root"; // domyślnie 'root' w XAMPP
              $password = ""; // domyślne hasło w XAMPP jest puste
              $dbname = "trainly";

              $conn = new mysqli($servername, $username, $password, $dbname);

              if ($conn->connect_error) {
                die("Błąd połączenia: " . $conn->connect_error);
              }

              $sql = "SELECT * FROM users WHERE User_ID='$id'";
              $result = $conn->query($sql);
          
              if ($result->num_rows > 0) {
                  $row = $result->fetch_assoc();
                  $user_name = $row['Name'];
                  $user_gender = $row['Gender'];
              }
              echo "<h2 id='welcome'><bold>Witaj $user_name!</bold></h2>";

              if($user_gender == 'female') {
                echo "<script>
                document.getElementsById('userinfo').style.backgroundImage = 'url('Graphics/userpage_hero_female.png')';
                </script>";
            }
            else {
                echo "<script>
                document.getElementsById('userinfo').style.backgroundImage = 'url('Graphics/userpage_hero.png')';
                </script>";
            }

              // CALENDAR
                // Pobierz listę dni z zaplanowanymi treningami z bazy danych
                $sql_calendar = "SELECT * FROM calendars WHERE User_ID='$id'";
                $result_calendar = $conn->query($sql_calendar);
        
        if ($result_calendar->num_rows > 0) {
          // Iteruj przez każdy wiersz z wynikami zapytania
          while ($row = $result_calendar->fetch_assoc()) {
              // Pobierz datę, opis treningu i godzinę z bazy danych
              $training_date = $row['Training_day'];
              $training_description = $row['Training_description'];
              $training_time = $row['Training_time'];
        
              // Oznacz odpowiedni dzień w kalendarzu jako zaplanowany
              echo "<script>document.getElementById('$training_date').classList.add('planned');</script>";
        
              // Wyświetl opis treningu i godzinę dla tego dnia
              //echo "<script>showPlannedTraining('day_" . $training_date . "');</script>";
          }
        }

$conn->close();
            ?>
          
          <br />
        </div>
        <div id="clock"></div>
        <hr />
        <a href="training.html" class="button" id="startTraining">
          Rozpocznij trening
        </a>
      </section>
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
      /*if ("<?php echo $user_gender; ?>" === "female") {
        // Jeśli użytkownik jest kobietą, zmieniamy tło elementu o klasie 'hero' na zdjęcie dla kobiet
        document.getElementByClassName('.userinfo').style.backgroundImage = "url('Trainly/Graphics/userpage_hero_female.png')";
      } */

      document.addEventListener("DOMContentLoaded", function () {
        const hamburger = document.querySelector(".hamburger");
        const menu = document.querySelector(".menu");

        // Dodajemy obsługę kliknięcia na hamburger
        hamburger.addEventListener("click", function () {
          // Dodajemy/Usuwaemy klasę "clicked" po kliknięciu
          this.classList.toggle("clicked");

          // Pokazujemy/Ukrywamy menu po kliknięciu
          menu.classList.toggle("active");
        });
      });

      document.addEventListener("DOMContentLoaded", function () {
        // Sprawdź czy parametr "show_lists" jest obecny w adresie URL
        var urlParams = new URLSearchParams(window.location.search);
        var showLists = urlParams.get("show_lists");
        var showCalendar = urlParams.get("show_calendar");
        var showStats = urlParams.get("show_stats");
        var showNotes = urlParams.get("show_notes");
        var showPanel = urlParams.get("show_panel");
        var createList = urlParams.get("create_list");
        var addList = urlParams.get("add_list");

        if (showLists === "true") {
          // Wyświetl sekcję z listami ćwiczeń
          showContent("lista");
        }
        if (showCalendar === "true") {
          // Wyświetl sekcję z kalendarzem
          showContent("kalendarz");
          markPlannedDays();
        }
        if (showStats === "true") {
          // Wyświetl sekcję ze statystykami
          showContent("statystyki");
        }
        if (showNotes === "true") {
          // Wyświetl sekcję z powiadomieniami
          showContent("powiadomienia");
        }
        if (showPanel === "true") {
          // Wyświetl sekcję z panelem użytkownika
          showContent("panel");
        }
        if (createList === "true") {
          var listName = urlParams.get("list_name");
          showSuccessNotification("Lista " + listName + " została utworzona!");
        }
        if (addList === "true") {
          var listName = urlParams.get("list_name");
          showSuccessNotification(
            "Lista " + listName + " została dodana do Twoich list!"
          );
        }
      });



      document.addEventListener("DOMContentLoaded", function() {
    // Funkcja do pobierania danych treningowych z serwera i oznaczania dni jako zaplanowane
    function markPlannedDays() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'pobierz_dane_treningowe.php', true); // Ustaw ścieżkę do skryptu PHP, który pobiera dane
        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    var plannedDays = response.plannedDays; // Tablica dni z przypisanymi treningami
                    plannedDays.forEach(function(dayData) {
                        var dayId = dayData.Training_day;
                        var description = dayData.Training_description;
                        var time = dayData.Training_time;
                        var dayElement = document.getElementById(dayId);
                        if (dayElement) {
                            dayElement.classList.add("planned");
                            // Ustaw opis treningu jako atrybut "title" dla elementu HTML
                            dayElement.setAttribute("title", "Opis treningu: " + description + "\nGodzina treningu: " + time);
                        }
                    });
                } else {
                    console.error("Wystąpił błąd podczas pobierania danych treningowych.");
                }
            } else {
                console.error("Wystąpił błąd podczas pobierania danych treningowych.");
            }
        };
        xhr.send();
    }

    // Wywołaj funkcję przy załadowaniu strony
    markPlannedDays();
    var calendarSidebar = document.getElementById('kalendarz');
    if (calendarSidebar) {
        calendarSidebar.addEventListener('click', markPlannedDays);
    }
});




      function plan(dayId) {
        document.getElementById("startTraining").style.display = "none";
        document.getElementById("planTraining").style.display = "block";
        handleDayClick(dayId);
      }

      function start(dayID) {
        document.getElementById("planTraining").style.display = "none";
        document.getElementById("startTraining").style.display = "block";
        document.getElementById("plannedInfo").style.display = "none";
        handleDayClick(null);
      }

      function handleDayClick(dayId) {
        var clickedDay = document.getElementById(dayId); // Pobierz kliknięty dzień na podstawie jego ID

        // Usuń klasę "last-clicked" ze wszystkich dni w kalendarzu
        var allDays = document.querySelectorAll(".days li span");
        allDays.forEach(function (day) {
          day.classList.remove("last-clicked");
        });

        // Dodaj klasę "last-clicked" tylko do klikniętego dnia
        clickedDay.classList.add("last-clicked");
      }

      function getDaysInMonth(month, year) {
        // Użyjemy miesiąca następnego, aby ustalić datę ostatniego dnia bieżącego miesiąca
        // Ponieważ dzień 0 zawsze oznacza ostatni dzień poprzedniego miesiąca
        return new Date(year, month + 1, 0).getDate();
      }

      // Funkcja do uzyskania dnia tygodnia dla pierwszego dnia miesiąca
      function getFirstDayOfMonth(month, year) {
        return new Date(year, month, 1).getDay();
      }

      // Funkcja do generowania pustych pól dla poprzednich dni w kalendarzu
      function generateEmptyCells(firstDayIndex) {
        var emptyCells = "";
        for (var i = 1; i < firstDayIndex; i++) {
          emptyCells += '<li><span class="empty"></span></li>';
        }
        return emptyCells;
      }

      // Funkcja otwierająca modal do planowania treningu
      function openPlanModal() {
        var modal = document.getElementById("planModal");
        modal.style.display = "block";
        document.getElementById("trainingDescriptionInput").focus();
      }

      // Funkcja zamykająca modal do planowania treningu
      function closePlanModal() {
        var modal = document.getElementById("planModal");
        document.getElementById("trainingDescriptionInput").value = "";
        document.getElementById("trainingTimeInput").value = "";
        modal.style.display = "none";
      }



      // Funkcja zapisująca plan treningowy
      function saveTrainingPlan() {
  var description = document.getElementById("trainingDescriptionInput").value;
  var time = document.getElementById("trainingTimeInput").value;
  var plannedDay = document.querySelector(".last-clicked");

  // Sprawdź czy wszystkie pola są uzupełnione
  if (!description || !time || !plannedDay) {
    alert("Wypełnij wszystkie pola!");
    return;
  }

  var dayId = plannedDay.id; // Pobierz ID klikniętego dnia

  // Przygotuj dane do wysłania
  var data = new FormData();
  data.append('description', description);
  data.append('time', time);
  data.append('dayId', dayId);

  // Użyj AJAX do wysłania danych do pliku PHP
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'plan.php', true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      // Pobierz odpowiedź z serwera
      var response = xhr.responseText;
      // Sprawdź odpowiedź - możesz obsłużyć różne przypadki, np. sukces lub błąd
      if (response === "success") {
        // Jeśli zapis do bazy danych powiódł się, wykonaj odpowiednie akcje, np. zamknięcie modala
        closePlanModal();
        // Wyświetl komunikat o sukcesie
        showSuccessNotification_calendar("Trening został zaplanowany pomyślnie!");
        // Oznacz kliknięty dzień jako zaplanowany
        plannedDay.classList.add("planned");
      } else {
        // Jeśli zapis do bazy danych nie powiódł się, wyświetl komunikat o błędzie
        alert("Wystąpił błąd podczas zapisywania planu treningowego. Spróbuj ponownie.");
      }
    } else {
      // Jeśli wystąpił błąd w połączeniu, wyświetl komunikat o błędzie
      alert('Wystąpił problem z połączeniem. Spróbuj ponownie.');
    }
  };
  xhr.send(data);
  //window.location.href = "userpage.php?show_calendar=true";
}


      // Funkcja obsługująca kliknięcie na dzień zaplanowany
      function showPlannedTraining(dayId) {
    var plannedDay = document.getElementById(dayId);
    var plannedTrainingDescription = document.getElementById("plannedTrainingDescription");
    var plannedTrainingTime = document.getElementById("plannedTrainingTime");

    // Sprawdź, czy dany dzień jest zaplanowany
    if (plannedDay.classList.contains("planned")) {
        // Pobierz opis i godzinę treningu z bazy danych
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'pobierz_dane_treningowe.php', true); // Ustaw ścieżkę do skryptu PHP, który pobiera dane
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    var plannedDays = response.plannedDays; // Tablica dni z przypisanymi treningami
                    // Znajdź odpowiedni dzień treningowy
                    var trainingData = plannedDays.find(function(dayData) {
                        return dayData.Training_day === dayId;
                    });
                    // Jeśli dane treningowe są dostępne, ustaw opis i godzinę treningu
                    if (trainingData) {
                        plannedTrainingDescription.innerText = trainingData.Training_description;
                        plannedTrainingTime.innerText = " || " + trainingData.Training_time;
                        // Wyświetl plannedInfo
                        document.getElementById("plannedInfo").style.display = "block";
                    } else {
                        // Jeśli brak danych treningowych dla danego dnia, ukryj plannedInfo
                        document.getElementById("plannedInfo").style.display = "none";
                    }
                } else {
                    console.error("Wystąpił błąd podczas pobierania danych treningowych.");
                }
            } else {
                console.error("Wystąpił błąd podczas pobierania danych treningowych.");
            }
        };
        xhr.send();
    } else {
        // Jeśli dzień nie jest zaplanowany, ukryj plannedInfo
        document.getElementById("plannedInfo").style.display = "none";
    }
}


      function showContent(section) {
        var content = document.getElementById("content");
        var html = "";
        // W zależności od klikniętego przycisku w sidebarze, generujemy odpowiednią zawartość
        switch (section) {
          case "panel":
            html = "<div class='userinfo' id='side_user'>";
            html += "<br><h2><bold>Witaj Radek!</bold></h2><br>";
            html += "</div>";
            html += "<div id='clock'></div>";
            html += "<hr>";
            html +=
              '<div class="button" id="user_button" onclick="startTraining()">Zacznij trening!</div>';
            document.getElementById("panel").classList.add("selected");
            document.getElementById("kalendarz").classList.remove("selected");
            document.getElementById("lista").classList.remove("selected");
            document.getElementById("statystyki").classList.remove("selected");
            document
              .getElementById("powiadomienia")
              .classList.remove("selected");
            break;
          case "kalendarz":
            var currentDate = new Date();
            var monthNames = [
              "STYCZEŃ",
              "LUTY",
              "MARZEC",
              "KWIECIEŃ",
              "MAJ",
              "CZERWIEC",
              "LIPIEC",
              "SIERPIEŃ",
              "WRZESIEŃ",
              "PAŹDZIERNIK",
              "LISTOPAD",
              "GRUDZIEŃ",
            ];
            var monthIndex = currentDate.getMonth();
            var year = currentDate.getFullYear();
            var activeDay = currentDate.getDate();
            var firstDayIndex = getFirstDayOfMonth(monthIndex, year);
            var html =
              '<h2 id="cals">Kalendarz</h2><div class="calendar"><div class="month"><ul><li><h2>';
            html += monthNames[monthIndex];
            html += "<span> ";
            html += year;
            html += "</span></h2></li></ul></div>";
            html +=
              '<ul class="weekdays"><li>Mo</li><li>Tu</li><li>We</li><li>Th</li><li>Fr</li><li>Sa</li><li>Su</li></ul>';
            html += '<ul class="days">';
            // Pobieramy liczbę dni w danym miesiącu
            var daysInMonth = new Date(year, monthIndex + 1, 0).getDate();
            html += generateEmptyCells(firstDayIndex);

            for (var i = 1; i <= daysInMonth; i++) {
              if (i === activeDay) {
                html +=
                  '<li><span class="active" onclick="start(this.id)">' +
                    i +
                  "</span></li>";
                }
                else {
                html +=
                  '<li><span class="inactive" onclick="plan(this.id); showPlannedTraining(\'day_' +
                  i +
                  '\')" id="day_' +
                  i +
                  '">' +
                  i +
                  "</span></li>";
              }
            }

            html += "</ul>";
            // opis zaplanowanego treningu
            html +=
              '<div class="planned_info" id="plannedInfo"><span id="plannedTrainingDescription"></span> <span id="plannedTrainingTime"></span></div>';
            // Dodajemy przycisk "Zaplanuj trening" i "Rozpocznij trening"
            html +=
              '<a href="training.html" class="button" id="startTraining">Rozpocznij trening</a>';
            html +=
              '<button class="button" id="planTraining" onclick="openPlanModal()">Zaplanuj trening</button>';
            html += "</div>";
            html += "</div></div><span class='mobile_cal'>";
            // Modal do planowania treningu
            html +=
              '<div id="planModal" class="modal"><div class="modal-content"><span class="close" onclick="closePlanModal()">&times;</span>';
            html +=
              '<h3>Zaplanuj trening</h3><input type="text" id="trainingDescriptionInput" placeholder="Opis treningu..."/>';
            html +=
              '<span class="icon"><i class="fa fa-clock-o"></i></span><input type="time" id="trainingTimeInput" />';
            html +=
              '<div class="button" onclick="saveTrainingPlan()">Zapisz</div></div></div>';
            // Komunikat o udanym zaplanowaniu treningu
            html +=
              '<div class="success_notification" id="success_notification"><span id="success_message"></span></div>';

            document.getElementById("kalendarz").classList.add("selected");
            document.getElementById("statystyki").classList.remove("selected");
            document.getElementById("lista").classList.remove("selected");
            document.getElementById("panel").classList.remove("selected");
            document
              .getElementById("powiadomienia")
              .classList.remove("selected");
            break;

          case "powiadomienia":
            document.getElementById("powiadomienia").classList.add("selected");
            document.getElementById("statystyki").classList.remove("selected");
            document.getElementById("lista").classList.remove("selected");
            document.getElementById("kalendarz").classList.remove("selected");
            document.getElementById("panel").classList.remove("selected");

            // Pobierz dane powiadomień z serwera
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'pobierz_powiadomienia.php', true); // Ustaw ścieżkę do skryptu PHP, który pobiera dane powiadomień
        xhr.onload = function () {
            if (xhr.status === 200) {
                var notifications = JSON.parse(xhr.responseText);
                var notificationHtml = "<h2 id='notes_h2'>Powiadomienia</h2><div class='notification_visible'>";
                notifications.forEach(function(notification) {
                    notificationHtml += generateNotification(notification.Title, notification.Content);
                });
                notificationHtml += "</div><span class='mobile_not'></span>";
                document.getElementById("content").innerHTML = notificationHtml;
            } else {
                console.error("Wystąpił błąd podczas pobierania danych powiadomień.");
            }
        };
        xhr.send();
            break;

          case "statystyki":
                        // Zaznacz odpowiedni przycisk na pasku bocznym
                        document.getElementById("statystyki").classList.add("selected");
            document.getElementById("kalendarz").classList.remove("selected");
            document.getElementById("lista").classList.remove("selected");
            document.getElementById("panel").classList.remove("selected");
            document.getElementById("powiadomienia").classList.remove("selected");
    // Pobierz dane statystyczne treningów z serwera
    var xhr_stat = new XMLHttpRequest();
    xhr_stat.open('GET', 'pobierz_dane_statystyczne.php', true); // Ustaw ścieżkę do skryptu PHP, który pobiera dane statystyczne
    xhr_stat.onload = function () {
        if (xhr_stat.status === 200) {
            var statistics = JSON.parse(xhr_stat.responseText);
            var stat_html = "<h2 id='stats'>Statystyki</h2><div class='statystyki'>";
            stat_html += "<h3>Całkowita liczba treningów w miesiącu</h3><div class='statistics'><div class='training'>" + statistics.Training_count + "</div></div>";
            stat_html += "<h3>Średni czas treningu</h3><div class='statistics'><div class='time'>" + statistics.Training_time + " min</div></div>";
            stat_html += "<h3>Spalone kalorie</h3><div class='statistics'><div class='calories'>" + statistics.Calories + " kcal</div></div>";
            stat_html += "<h3>Średnia intensywność treningu</h3><div class='statistics'><div class='intensitivity'>" + statistics.Intensity + "</div></div>";
            stat_html += "</div><span class='mobile_stats'></span>";

            // Wyświetl dane w sekcji statystyk
            document.getElementById("content").innerHTML = stat_html;

        } else {
            alert("Wystąpił błąd podczas pobierania danych statystycznych treningów.");
        }
    };
    xhr_stat.send();
    break;


          case "lista":
            html = '<h2 id="lists">Listy ćwiczeń</h2><div class="columns">';
            html +=
              '<a href="your_list.html"><ul class="opcje_listy"><li class="header"><h2>Twoje listy<h2></li>';
            html += "<li class='your_list'></li></ul></a>";
            html +=
              '<a href="create_list.html"><ul class="opcje_listy"><li class="header"><h2>Stwórz listę<h2></li>';
            html += "<li class='create_list'></li></ul></a>";
            html +=
              '<a href="our_list.html"><ul class="opcje_listy"><li class="header"><h2>Nasze listy<h2></li>';
            html += "<li class='our_list'></li></ul></a>";
            html += "</div>"; //class columns
            html +=
              '<div class="success_notification" id="success_notification"><span id="success_message"></span></div>';
            document.getElementById("lista").classList.add("selected");
            document.getElementById("statystyki").classList.remove("selected");
            document.getElementById("kalendarz").classList.remove("selected");
            document.getElementById("panel").classList.remove("selected");
            document
              .getElementById("powiadomienia")
              .classList.remove("selected");
            break;

          default:
            html = "<p>Nieznany dział</p>";
        }

        // Wstawiamy wygenerowaną zawartość do sekcji content
        content.innerHTML = html;
      }

      function generateNotification(title, n_content) {
        var notification = document.createElement("div");
        notification.classList.add("notification");
        notification.innerHTML =
          "<strong>" +
          title +
          "</strong><br><span style='display:none;'>" +
          n_content +
          "</span>";

        return notification.outerHTML;
      }

      document
        .getElementById("content")
        .addEventListener("click", function (event) {
          var target = event.target;

          // Sprawdzamy czy kliknięcie dotyczyło elementu z klasą "notification"
          if (target.classList.contains("notification")) {
            // Pobieramy tytuł i zawartość powiadomienia
            var title = target.querySelector("strong").textContent;
            var content = target.querySelector("span").textContent;

            // Otwieramy modal z tytułem i zawartością powiadomienia
            openModal(title, content);
          }
        });

      function openModal(title, content) {
        var modal = document.getElementById("overlay_notification");
        var modalTitle = document.getElementById("notification-title");
        var modalContent = document.getElementById("notification-content");

        modalTitle.textContent = title;
        modalContent.innerHTML = content;

        modal.style.display = "block";
      }

      // Funkcja do zamykania modalu
      function closeModal() {
        var modal = document.getElementById("overlay_notification");
        modal.style.display = "none";
      }

      function updateClock() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();

        // Formatowanie czasu
        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        // Wyświetlanie czasu w elemencie o id "clock"
        document.getElementById("clock").textContent =
          hours + ":" + minutes + ":" + seconds;
      }

      // Wywołanie funkcji updateClock co sekundę
      setInterval(updateClock, 1000);

      // Wywołanie funkcji updateClock przy załadowaniu strony, aby od razu wyświetliła się aktualna godzina
      updateClock();

      // Tutaj dodajemy przypisanie szerokości dla klasy .training
      var numberOfTrainingDays = 7;

      var currentDate = new Date();
      var currentMonth = currentDate.getMonth();
      var currentYear = currentDate.getFullYear();
      var totalDaysInMonth = getDaysInMonth(currentMonth, currentYear);

      // Oblicz szerokość dla sekcji .training na podstawie liczby dni treningowych i całkowitej liczby dni w miesiącu
      var trainingWidth = (numberOfTrainingDays / totalDaysInMonth) * 100;
      // Ustaw szerokość dla sekcji .training
      document.documentElement.style.setProperty(
        "--training-width",
        dayWidth + "%"
      );

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
          showContent("lista");
        }, 3000);
      }

      function showSuccessNotification_calendar(message) {
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
    </script>
  </body>
</html>
