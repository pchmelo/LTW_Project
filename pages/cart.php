<?php

  declare(strict_types = 1);

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/user.class.php');

  $session = new Session();

  $db = getDatabaseConnection();


if (!$session->isLoggedIn()) {
    header('Location: LogIn.php');
    exit;
}
else if (user::getUserByUsername($db,$_GET['username'])->id != $session->getId()) {
    header('Location: LogIn.php');
    exit;
}

  require_once(__DIR__ . '/../database/product.class.php');
  require_once(__DIR__ . '/../database/order.class.php');


  require_once(__DIR__ . '/../templates/landingPage.tpl.php');
  require_once(__DIR__ . '/../templates/cart.tpl.php');



  $user = user::getUserByUsername($db, $_GET['username']);
  $products = order::getUnprocessedOrdersByBuyerID($db, $user->id);

  drawHeader($db,$session);
  drawCart($session, $products,$db);
  //drawCartInfo($session,$products);