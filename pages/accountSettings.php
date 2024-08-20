<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/user.class.php');

  $db = getDatabaseConnection();


if (!$session->isLoggedIn()) {
    header('Location: index.php');
    exit;
}
else {
    $user = User::getUserByUsername($db, $_GET['username']);
    if ($user->id != $session->getId()) {
        header('Location: index.php');
        exit;
    }
}

  require_once(__DIR__ . '/../database/product.class.php');

  require_once(__DIR__ . '/../templates/landingPage.tpl.php');
  require_once(__DIR__ . '/../templates/profileSettings.tpl.php');


  $user = User::getUserByUsername($db, $_GET['username']);
  $products = Product::getProductsFromUser($db, $user->id);

  drawHeader($db, $session);
  drawEditCredentialsForm($user);
  drawSideMenu($db, $session);