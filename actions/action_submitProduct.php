<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/user.class.php');

if ($_POST['csrf'] !== $_SESSION['csrf']) {
    die("CSRF token validation failed");
}

$db = getDatabaseConnection();

$errors = [];
$emptyFields = [];
$filledFields = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fields = ['name', 'price', 'category', 'brand', 'model', 'size', 'condition', 'description', 'imageUrl', 'SellerID'];
    foreach ($fields as $field) {
        if (empty($_POST[$field])) {
            $emptyFields[] = $field;
        } else {
            $filledFields[] = $field;
        }
    }

    if (!empty($emptyFields)) {
        $errors[] = "The following fields are empty: " . implode(", ", $emptyFields);
    }

    if (empty($errors) && !is_numeric($_POST['price'])) {
        $errors[] = "Price must be numerical.";
    }

    if (empty($errors)) {
            $product = new Product(null, htmlspecialchars($_POST['name']), 
            htmlspecialchars($_POST['price']), 
            htmlspecialchars($_POST['category']), 
            htmlspecialchars($_POST['brand']), 
            htmlspecialchars($_POST['model']), 
            htmlspecialchars($_POST['size']), 
            htmlspecialchars($_POST['condition']), 
            htmlspecialchars($_POST['description']), 
            $_POST['imageUrl'], 
            $_POST['SellerID'],1);

            try {
                $product->create($db);
                $id = $db->lastInsertId();
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }
    

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $seller = User::getUserById($db, $_POST['SellerID']);
        header('Location: ../pages/productSubmit.php?username=' . $seller->username);
        exit();
    }

    header('Location: ../pages/product.php?id=' . $id);                
    exit();
} else {
    $errors[] = "Form not submitted.";
    $_SESSION['errors'] = $errors;
    $seller = User::getUserById($db, $_POST['SellerID']);
    header('Location: ../pages/productSubmit.php?username=' . $seller->username);
    exit();
}