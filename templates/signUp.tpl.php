<?php
    declare(strict_types = 1); 
    require_once(__DIR__ . '/../utils/session.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Signup</title>
        <link rel="stylesheet" href="../css/loginPage.css">
        <link rel="stylesheet" href="../css/landingPage.css">
        <script src="../javascript/searchbar.js" defer></script>
    </head>
    <?php function drawSignupForm($session, $isValid) { ?>
    <body>
        <div class="containerLogin">
            <div style="margin-top: 4em">
        
        <?php if($isValid): ?>
            <em><?php echo $isValid; ?></em>
        <?php endif; ?>

        <form action="../actions/action_signup.php" method="post">
        <h1>Sign Up</h1>
            <div>
                <label for="username"></label>
                <input placeholder="Username" type="text" id="username" name="username" minlength="3" maxlength="20" required>
            </div>
            <div>
                <label for="email"></label>
                <input placeholder="E-mail" type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password"></label>
                <input placeholder="Password" type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="firstName"></label>
                <input placeholder="First Name" type="text" id="firstName" minlength="2" maxlength="20" name="firstName">
            </div>
            <div>
                <label for="lastName"></label>
                <input placeholder="Last Name" type="text" id="lastName" minlength="2" maxlength="20" name="lastName">
            </div>
            <button type="submit">Sign up</button>
        </form>
        <div class="register">
        <p>Already have an Account? <a href="LogIn.php" id="register">Log In</a></p></div>

        </div></div>
    </body>
    <?php } ?>
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