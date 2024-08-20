<?php

/*CREATE TABLE Replies (
  ReplyID INTEGER PRIMARY KEY AUTOINCREMENT,
  CommentID INTEGER,
  AuthorID INTEGER,
  ReplyText TEXT,
  ReplyDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (CommentID) REFERENCES Comments(CommentID) ON DELETE CASCADE ON UPDATE NO ACTION,
  FOREIGN KEY (AuthorID) REFERENCES Users(UserID) ON DELETE CASCADE ON UPDATE NO ACTION
);*/

declare(strict_types = 1);

class Reply {
    public $replyID;
    public $commentID;
    public $authorID;
    public $replyText;

    public function __construct($replyID, $commentID, $authorID, $replyText) {
        $this->replyID = $replyID;
        $this->commentID = $commentID;
        $this->authorID = $authorID;
        $this->replyText = $replyText;
    }

    public function create($db) {
        $stmt = $db->prepare("INSERT INTO Replies (ReplyID, CommentID, AuthorID, ReplyText) VALUES (?, ?, ?, ?)");
        $stmt->execute(array($this->replyID, $this->commentID, $this->authorID, $this->replyText));
    }

    public static function getRepliesFromComment($db, $commentID) {
        $stmt = $db->prepare("SELECT * FROM Replies WHERE CommentID = ?
        ORDER BY ReplyID ASC");
        $stmt->execute(array($commentID));
        $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function($reply) {
            return new Reply($reply['ReplyID'], $reply['CommentID'], $reply['AuthorID'], $reply['ReplyText']);
        }, $replies);
    }

    public function delete($db) {
        $stmt = $db->prepare("DELETE FROM Replies WHERE ReplyID = ?");
        $stmt->execute(array($this->replyID));
    }


    public static function countReplysFromComment($db, $commentID) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM Replies WHERE CommentID = ?");
        $stmt->execute(array($commentID));
        return $stmt->fetchColumn();
    }
}
