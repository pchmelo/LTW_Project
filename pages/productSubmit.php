<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/caracteristicas.class.php');


$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    header('Location: LogIn.php');
    exit;
}
else if (user::getUserByUsername($db,$_GET['username'])->id != $session->getId()) {
    header('Location: index.php');
    exit;
}

require_once(__DIR__ . '/../database/product.class.php');

$user = user::getUserByUsername($db, $_GET['username']);
$products = Product::getProductsFromUser($db,$user->id);

$categories = Caracteristicas::getCaracteristicasByType($db, 'Categories');
$categoryValues = [];
foreach ($categories as $category) {
    $categoryValues[] = $category->caracValue;
}

$tamanhos = Caracteristicas::getCaracteristicasByType($db, 'Tamanho');
$tamanhoValues = [];
foreach ($tamanhos as $tamanho) {
    $tamanhoValues[] = $tamanho->caracValue;
}

$conditions = Caracteristicas::getCaracteristicasByType($db, 'Condition');
$conditionValues = [];
foreach ($conditions as $condition) {
    $conditionValues[] = $condition->caracValue;
}

require_once(__DIR__ . '/../templates/landingPage.tpl.php');
require_once(__DIR__ . '/../templates/submit.tpl.php');

$errors = [];
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']); 
}

drawHeader($db,$session);
drawSellingForm($user, $categoryValues, $conditionValues, $tamanhoValues, $errors, $session); 
drawFooter($db, $session);