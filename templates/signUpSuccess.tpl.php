<?php
    declare(strict_types = 1); 
    require_once(__DIR__ . '/../utils/session.php');
?>

<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Signup</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
        </head>
        <?php function drawSignupSuccess($session) { ?>
        <body>
            <h1>Signup</h1>
            <p>Signup successful. 
                You can now <a href="LogIn.php">Log in</a>
            </p>
        </body>
        <?php } ?>
</html>
