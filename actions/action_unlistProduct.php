<?php
require_once(__DIR__ . '/../database/order.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

$productID = $_POST['productID'];

$product = new Product($productID,null,null,null,null,null,null,null,null,null,null,null);
$product->delete($db);