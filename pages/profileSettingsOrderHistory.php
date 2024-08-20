<?php
declare(strict_types = 1);

// Enable error reporting
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    header('Location: index.php');
    exit;
}
else if (user::getUserByUsername($db,$_GET['username'])->id != $session->getId()) {
    header('Location: index.php');
    exit;
}

require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/order.class.php');

try {
    $user = user::getUserByUsername($db, $_GET['username']);
    $buyerID = $user->id;
} catch (Exception $e) {
    echo 'User retrieval error: ' . $e->getMessage();
    exit;
}

try {
    $products = Order::getProcessedOrdersByBuyerId($db, $buyerID);
} catch (Exception $e) {
    echo 'Order retrieval error: ' . $e->getMessage();
    exit;
}

try {
    $groupedProducts = Order::mapProductsToOrderGroups($db, $products);
} catch (Exception $e) {
    echo 'Grouping error: ' . $e->getMessage();
    exit;
}

$errors = $_SESSION['errors'] ?? [];

require_once(__DIR__ . '/../templates/landingPage.tpl.php');
require_once(__DIR__ . '/../templates/profileSettings.tpl.php');

$admin = false;

drawHeader($db,$session);
drawOrderHistory($db, $session, $products, $groupedProducts,$admin);
drawSideMenu($db, $session);