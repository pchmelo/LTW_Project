<?php



declare(strict_types = 1);


class Comment {
    public $commentID;
    public $productID;
    public $author;
    public $content;


    public function __construct($commentID, $productID, $author, $content) {
        $this->commentID = $commentID;
        $this->productID = $productID;
        $this->author = $author;
        $this->content = $content;
    }

    public function create($db) {
        $stmt = $db->prepare("INSERT INTO Comments (CommentID, ProductID, AuthorID, Content) VALUES (?, ?, ?, ?)");
        $stmt->execute(array(null, $this->productID, $this->author,$this->content));
    }


    public static function getCommentsFromProduct($db, $productID) {
        $stmt = $db->prepare("SELECT * FROM Comments WHERE ProductID = ?
        ORDER BY CommentID ASC");
        $stmt->execute(array($productID));
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($comment) {
            return new Comment($comment['CommentID'], $comment['ProductID'], $comment['AuthorID'], $comment['Content']);
        }, $comments);
    }

    public function delete($db) {
        $stmt = $db->prepare("DELETE FROM Comments WHERE CommentID = ?");
        $stmt->execute(array($this->commentID));
    }


}