<?php
  declare(strict_types = 1);
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);
  

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/product.class.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/order.class.php');


  $db = getDatabaseConnection();

  $user = user::getUserByUsername($db, $_GET['username']);

  if ($user === null) { header('Location: /index.php'); exit(); }

  $products = Product::getProductsFromUser($db,$user->id);
  $cartproducts = Order::getUnprocessedOrdersByBuyerID($db, $session->getId());

  require_once(__DIR__ . '/../templates/landingPage.tpl.php');
  require_once(__DIR__ . '/../templates/profile.tpl.php');

  drawHeader($db,$session);
  drawProfile($db, $session, $user);
  drawSelling($session, $cartproducts, $products, $db);
  drawFooter($db, $session);
