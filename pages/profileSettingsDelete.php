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
else if (user::getUserByUsername($db,$_GET['username'])->id != $session->getId()) {
    header('Location: index.php');
    exit;
}

require_once(__DIR__ . '/../database/product.class.php');

$user = user::getUserByUsername($db, $_GET['username']);
$products = Product::getProductsFromUser($db,$user->id);

require_once(__DIR__ . '/../templates/landingPage.tpl.php');
require_once(__DIR__ . '/../templates/profileSettings.tpl.php');

$errors = $_SESSION['errors'] ?? [];

drawHeader($db,$session);
drawDeleteForm($user, $errors, $session);
drawSideMenu($db, $session);