<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/product.class.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/order.class.php');

  require_once(__DIR__ . '/../templates/landingPage.tpl.php');

  $db = getDatabaseConnection();
  
  if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
  }
  else{
    $searchValue = '';
  }

  $categories = Product::getAllCategories($db);
  $sizes = Product::getAllTamanhos($db);
  $brands = Product::getAllBrands($db);
  $models = Product::getAllModels($db);
  $conditions = Product::getAllConditions($db);


  $products = Product::getAvailableProducts($db);
  $cartproducts = Order::getUnprocessedOrdersByBuyerID($db, $session->getId());

  drawHeader($db,$session);
  require_once(__DIR__ . '/../templates/search.tpl.php');
  productsGridSearch($session,$cartproducts, $products, $db);
  drawFooter($db, $session);
  