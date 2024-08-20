<?php
/*require_once(__DIR__ . '/../database/question.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

$productID = $_POST['productID'];
$buyerID = $_POST['buyerID'];
$sellerID = $_POST['sellerID'];
$questionText = $_POST['question'];


$question = new Question(null, $productID, $buyerID, $sellerID, $questionText, null);
$question->create($db);


header('Location: ' . '/pages/product.php?id=' . $productID);