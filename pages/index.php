<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/product.class.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/order.class.php');
  require_once(__DIR__ . '/../database/caracteristicas.class.php');


  $db = getDatabaseConnection();

  $products = Product::getAvailableProducts($db);
  $cartproducts = Order::getUnprocessedOrdersByBuyerID($db, $session->getId());
  $categories = Product::getAllCategories($db);


  require_once(__DIR__ . '/../templates/landingPage.tpl.php');


  drawHeader($db, $session);
  heroSection($session);
  cathegorySection($session,$products, $categories, $db);
  productsGrid($session,$cartproducts, $products, $db);
  drawFooter($db, $session);
