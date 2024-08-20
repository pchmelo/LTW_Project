<?php
  declare(strict_types = 1);
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);
  
  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  //var_dump($session->getCsrf());
  
  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/product.class.php');
  require_once(__DIR__ . '/../database/user.class.php');
  require_once(__DIR__ . '/../database/order.class.php');
  require_once(__DIR__ . '/../database/caracteristicas.class.php');


  $db = getDatabaseConnection();

  $user = user::getUserById($db, $session->getId());
  //var_dump($user);

  if ($user === null) { header('Location: /index.php'); exit(); }
  if ($user->rank != 1) { header('Location: /index.php'); exit(); }

  require_once(__DIR__ . '/../templates/landingPage.tpl.php');
  require_once(__DIR__ . '/../templates/admin.tpl.php');

  $selector = $_GET["selector"];

  drawHeader($db, $session);

  if($selector == 1){
    drawUsersAdmin($session, $user, $db);
  }
  else if($selector == 2){
    require_once(__DIR__ . '/../templates/profileSettings.tpl.php'); 
    drawOrdersAdmin($session, $user, $db);
  }
  else if ($selector >= 3){
    drawCaracteristicsAdmin($session, $user, $db);
  }
  
  drawSideMenuAdmin($session, $db);
