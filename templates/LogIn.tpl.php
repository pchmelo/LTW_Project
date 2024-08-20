<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link href="../css/landingPage.css" rel="stylesheet">
        <link href="../css/loginPage.css" rel="stylesheet">
        <script src="../javascript/searchbar.js" defer></script>


    </head>
    <body>
        <?php function drawLoginForm($is_valid) { ?>
        <div class="containerLogin">
            <div>

            <?php
                if (isset($_SESSION['success'])) {
                    echo '<div style="color: #155724; background-color: #d4edda; padding: 10px; margin: 10px;">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
            ?>

        <form method="post">
        <h1>Login</h1>
            <div>
                <label for="username"></label>
                <input type="text" id="username" name="username" placeholder="Username or Email" value="<?php echo htmlspecialchars($_POST["username"] ?? "")?>">
              </div>
            <div>
                <label for="password"></label>
                <input type="password" id="password" placeholder="Password" name="password">
            </div>
            <button type="submit">Login</button>
        </form>
            <div class="register">
                <p>Don't have an account? <a href="SignUp.php" id="register">Sign Up</a></p>
            </div>
            <?php if($is_valid): ?>
            <em>Invalid username/email or password</em>
        <?php endif; ?>
        </div>

            <?php } ?>
    </body>
</html>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var element = document.querySelector('h1');
    var element2 = document.getElementById('searchInput');
    var element3 = document.getElementById('searchImg');
    var element4 = document.getElementById('nomeheader');
    var anuncio = document.getElementById('announce');
    anuncio.classList.add('scrolled');
    element3.style.display = 'block';
    element2.style.display = 'block';
    element.classList.add('scrolled');
    element4.classList.remove('name');
  });
</script>