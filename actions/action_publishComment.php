<?php
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/comment.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

if ($_POST['csrf'] !== $_SESSION['csrf']) {
    die("CSRF token validation failed");
}

$db = getDatabaseConnection();

$productID = $_POST['productID'];
$userID = $_POST['userID'];
$content = htmlspecialchars($_POST['content']);

$comment = new Comment(null,$productID,$userID,$content);
$comment->create($db);

echo $db->lastInsertId();