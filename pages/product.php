<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();
  //var_dump($session); 

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/product.class.php');
  require_once(__DIR__ . '/../database/order.class.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/comment.class.php');
  require_once(__DIR__ . '/../database/reply.class.php');

  $db = getDatabaseConnection();
  //var_dump($db);

  $product = Product::getProductById($db, $_GET['id']);
  $comments = Comment::getCommentsFromProduct($db, $product->id);



  if (!$product) {
    header('Location: ' . '/index.php');
    exit();
  }

  if (!$product->isAvailable) {
    header('Location: ' . '/index.php');
    exit();
  }

  $categoryproducts = Product::getProductsFromCategory($db, $product->category);
  //var_dump($categoryproducts); 

  $cartproducts = Order::getUnprocessedOrdersByBuyerID($db, $session->getId());
  //var_dump($cartproducts);

  $availableCategoryProducts = array_filter($categoryproducts, function($product) {
    return $product->isAvailable;
  });
  //var_dump($availableCategoryProducts); 

  $next = Product::findNextAvailableProduct($db, $product->id);
  $prev = Product::findPreviousAvailableProduct($db, $product->id);

  $nextProductId = $next ? $next->id : null;
  $prevProductId = $prev ? $prev->id : null;



  


  require_once(__DIR__ . '/../templates/product.tpl.php');
  require_once(__DIR__ . '/../templates/landingPage.tpl.php');

  drawHeader($db,$session);
  drawProductInfo($session, $cartproducts, $product, $nextProductId, $prevProductId,$db);
  drawComments($session,$product, $comments, $db);

  if(count($categoryproducts)-1 > 0){
    drawRelated($session, $categoryproducts, $product, $db);
  }
  drawFooter($db, $session);
