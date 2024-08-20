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
    $shippingPrice = $_POST['shippingPrice'];

    $user = user::getUserByUsername($db, $buyerID);
    $products = order::getUnprocessedOrdersByBuyerID($db, $user->id);

    $group = order::getHighestGroupValue($db) + 1;

    $success = true;
    foreach ($products as $product) {
        $order = new Order(null,$product->id, $user->id, $product->SellerID, null, 1, $group, $shippingPrice);
        $order->process($db);
        if (!$order->isProcessed) {
            $success = false;
            break;
        }
    }

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