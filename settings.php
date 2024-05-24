<?php
              session_start();
              $id = $_SESSION['user_id'];

              require_once "config.php";

              $conn = new mysqli($servername, $username, $password, $dbname);

              if ($conn->connect_error) {
                die("Błąd połączenia: " . $conn->connect_error);
              }

              $sql = "SELECT * FROM users WHERE User_ID='$id'";
              $result = $conn->query($sql);
          
              if ($result->num_rows > 0) {
                  $row = $result->fetch_assoc();
                  $user_name = $row['Name'];
                  $user_surname = $row['Surname'];
                  $user_gender = $row['Gender'];
                  $user_age = $row['Age'];
                  $user_phone = $row['Phone'];
                  $user_email = $row['Login'];
              }

              $conn->close();
            ?>
<?php
// Sprawdzenie, czy parametr "success" jest ustawiony na "true"
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            var successNotification = document.getElementById("success_notification");
            successNotification.style.display = "block";
            setTimeout(function () {
                successNotification.style.display = "none";
            }, 3000);
        });
    </script>';
}
else if (isset($_GET['error']) && $_GET['error'] == 'same_password') {
  echo '<script>
      document.addEventListener("DOMContentLoaded", function () {
          var errorNotification = document.getElementById("error_notification");
          errorNotification.style.display = "block";
          setTimeout(function () {
              errorNotification.style.display = "none";
          }, 3000);
      });
  </script>';
}
else {

}
?>

<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8" />
    <title>Trainly - Ustawienia</title>
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
  </head>

  <body class="fade-in">
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
            <a href="userpage.php">
              <li>Twój panel</li>
            </a>
            <a href="settings.php" class="current_site">
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
    <main class="set_mob">
      <div class="settings">
        <br />
        <h2>Ustawienia konta</h2>
        <br />
        <form id="settingsForm"  method="POST" action="update.php" onsubmit="return submitForm(event)">  
          <label style="font-weight: bold; color: #734a40;" class='set_label' for="name">Imię:</label>
            <input
              type="text"
              id="name"
              name="name"
              placeholder="Imię"
              value="<?php echo $user_name; ?>"
              required
            />
            <label style="font-weight: bold; color: #734a40;" class='set_label' for="name">Nazwisko:</label>
          <input
            type="text"
            id="surname"
            name="surname"
            placeholder="Nazwisko"
            value=<?php echo $user_surname; ?>
            required
          />
          <label style="font-weight: bold; color: #734a40;" class='set_label' for="age">Wiek:</label>
          <input
            type="number"
            id="age"
            name="age"
            placeholder="Wiek"
            value=<?php echo $user_age; ?>
            required
          />
          <label style="font-weight: bold; color: #734a40;" class='set_label' for="phone">Numer telefonu:</label>
          <input
            type="tel"
            id="phone"
            name="phone"
            placeholder="Numer telefonu"
            value=<?php echo $user_phone; ?>
            required
          />
          <label style="font-weight: bold; color: #734a40;" class='set_label' for="email">Adres email:</label>
          <input
            type="email"
            id="email"
            name="email"
            value=<?php echo $user_email; ?>
            placeholder="Adres email"
            disabled
          />
          <hr />
          <label style="font-weight: bolder; color: #734a40;" class='set_label' for="email">Zmień hasło:</label>
          <input
            type="password"
            id="password"
            name="password"
            minlength="8"
            placeholder="Nowe hasło"
          />
          <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            placeholder="Potwierdź nowe hasło"
          />
          <div id="password_error">Hasła nie są identyczne.</div>
          <hr />
          <input type="submit" value="Zapisz zmiany" />
        </form>
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

    <!-- Div dla powiadomienia -->
    <div class="success_notification" id="success_notification">
      <span id="success_message">Zmiany zostały zapisane poprawnie!</span>
    </div>
    <div class="success_notification" id="error_notification">
      <span id="error_message" style="color: red; font-weight: bold">Nowe hasło musi się różnić od obecnego!</span>
    </div>



    <script>
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

      function submitForm(event) {
    event.preventDefault(); // Anuluj domyślną akcję przekierowania formularza
    var isFormValid = validateForm();
    if (isFormValid) {
                  // Wyczyść pola z hasłem i powtórzeniem hasła
            document.getElementById("settingsForm").submit();
            document.getElementById("password").value = "";
            document.getElementById("confirm_password").value = "";
    }
    return isFormValid;
}

      function validateForm() {
        var password = document.getElementById("password").value;
        var confirm_password =
          document.getElementById("confirm_password").value;
        var password_error = document.getElementById("password_error");

        if (password !== confirm_password) {
          password_error.style.display = "block";
          return false;
        } else {
          password_error.style.display = "none";
          return true;
        }
      }
    </script>
  </body>
</html>
