<?php
require_once(__DIR__ . '/../database/order.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$db = getDatabaseConnection();

$productID = $_POST['productID'];
$buyerID = $_POST['buyerID'];
$user = User::getUserByUsername($db, $buyerID);

echo "Product ID: " . $productID . "<br>";
echo "Buyer ID: " . $buyerID . "<br>";

Order::deleteFromCart($db, $productID, $user->id);

