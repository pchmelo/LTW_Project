<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error) {
        error_log(print_r($error, true));
        http_response_code(500);
        echo "Server Error";
    }
});

require_once(__DIR__ . '/../database/order.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

try {
    $db = getDatabaseConnection();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $buyerID = $_POST['userId'];
    $user = user::getUserByUsername($db, $buyerID);
    $productID = $_POST['productId'];
    $product = Product::getProductById($db, $productID);
    $sellerID = $product->SellerID;
    $shippingPrice = $_POST['shippingPrice'];

    $group = order::getHighestGroupValue($db) + 1;


    $order = new Order(null, $productID, $user->id, $sellerID, null, 0, $group, $shippingPrice);

    $order->create($db);
    $order->process($db);


    $success = true;

    if ($success) {
        echo "Success";
    } else {
        echo "Failure";
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo "Server Error";
}