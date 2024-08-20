<?php
require_once(__DIR__ . '/../database/order.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

$productID = $_POST['productID'];
$buyerID = $_POST['buyerID'];
$sellerID = $_POST['sellerID'];

$order = new Order(null, $productID, $buyerID, $sellerID, null, 0, null, null);
$order->create($db);
