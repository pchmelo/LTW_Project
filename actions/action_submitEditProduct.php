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
        $errors[] = "The following fields must be filled: " . implode(', ', $emptyFields);
    }

    if (empty($errors) && !is_numeric($_POST['price'])) {
        $errors[] = "Price must be numerical.";
    }

    if (empty($errors)) {
            $product = Product::getProductById($db, $_POST['productId']);
    
            $product->name = htmlspecialchars($_POST['name']);
            $product->price = htmlspecialchars($_POST['price']);
            $product->category = htmlspecialchars($_POST['category']);
            $product->brand = htmlspecialchars($_POST['brand']);
            $product->model = htmlspecialchars($_POST['model']);
            $product->tamanho = htmlspecialchars($_POST['size']);
            $product->condition = htmlspecialchars($_POST['condition']);
            $product->description = htmlspecialchars($_POST['description']);
            $product->imageUrl = $_POST['imageUrl'];
            $product->SellerID = htmlspecialchars($_POST['SellerID']);
            $product->isAvailable = 1;
    
            try {
                $product->save($db);
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }
    

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;

        $seller = User::getUserById($db, $_POST['SellerID']);
        header('Location: ../pages/productSubmitEdit.php?username=' . $seller->username . '&prod_id=' . $_POST['productId']);
        var_dump($seller->username);
        exit();
    }

    $seller = User::getUserById($db, $product->SellerID);
    header('Location: ../pages/product.php?id=' . $_POST['productId']);                
    exit();
} else {
    $errors[] = "Form not submitted.";
    $_SESSION['errors'] = $errors;
    $seller = User::getUserById($db, $_POST['SellerID']);
    header('Location: ../pages/productSubmitEdit.php?username=' . $seller->username . '&prod_id=' . $_POST['productId']);

    exit();
}