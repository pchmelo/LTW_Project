<?php

declare(strict_types = 1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

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

$user = user::getUserByUsername($db, $_GET['username']);
$product = Product::getProductById($db, $_GET['prod_id']);

require_once(__DIR__ . '/../templates/landingPage.tpl.php');
require_once(__DIR__ . '/../templates/cartSingleProduct.tpl.php');

drawHeader($db,$session);
drawBuyProduct($session, $product, $db);
//drawCartInfo($session,$products);