<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_POST['csrf'] !== $_SESSION['csrf']) {
    die("CSRF token validation failed");
}

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/product.class.php');
require_once(__DIR__ . '/../database/comment.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/reply.class.php');

$db = getDatabaseConnection();

$commentID = $_POST['commentID'];
$userID = $_POST['userID'];
$content = htmlspecialchars($_POST['content']);
var_dump($commentID);
var_dump($userID);
var_dump($content);



$reply = new Reply(null,$commentID,$userID,$content);
$reply->create($db);

echo 'Success';