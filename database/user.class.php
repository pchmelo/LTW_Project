<?php
declare(strict_types = 1);
class user{
    /*
    CREATE TABLE Users (
        UserID INTEGER PRIMARY KEY NOT NULL,
        Username VARCHAR(50) UNIQUE NOT NULL,
        Password VARCHAR(255) NOT NULL,  -- Store hashed passwords securely
        Email VARCHAR(100) UNIQUE NOT NULL,
        FirstName VARCHAR(50),
        LastName VARCHAR(50),
        Rank INTEGER DEFAULT 0,  -- 0 for regular users, 1 for admins
        CONSTRAINT usernameLengthCheck CHECK (LENGTH(Username) >= 2),  -- Enforce minimum length
        CONSTRAINT emailLengthCheck CHECK (LENGTH(Email) >= 11)  -- Enforce minimum length
    );
    */

    public $id;
    public $username;
    public $password;
    public $email;
    public $firstName;
    public $lastName;
    public $rank;

    public function __construct($id = null, $username = null, $password = null, $email = null, $firstName = null, $lastName = null, $rank = 0) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->rank = $rank;
    }

    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function create($db) {
        $stmt = $db->prepare('
            INSERT INTO Users (Username, Password, Email, FirstName, LastName, Rank) 
            VALUES (:username, :password, :email, :firstName, :lastName, :rank)
        ');
    
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':firstName', $this->firstName);
        $stmt->bindParam(':lastName', $this->lastName);  
        $stmt->bindParam(':rank', $this->rank);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 19) {
                throw new Exception('The email or the username already exists');
            } else {
                throw $e;
            }
        }
    }

    public function save($db) {
        $stmt = $db->prepare('
        UPDATE Users SET Username = ?, Password = ?, Email = ?, FirstName = ?, LastName = ?, Rank = ? 
        WHERE UserID = ?
      ');
    
        $stmt->execute(array($this->username, $this->password, $this->email, $this->firstName, $this->lastName, $this->rank, $this->id));
    }

    public function delete($db) {
        $stmt = $db->prepare('UPDATE Products SET isAvailable = 0 WHERE SellerID = ?');
        $stmt->execute(array($this->id));
    
        $stmt = $db->prepare('DELETE FROM Users WHERE UserID = ?');
        $stmt->execute(array($this->id));

        $stmt = $db->prepare('DELETE FROM Orders 
        WHERE SellerID = ? AND isProcessed = 0');
        $stmt->execute(array($this->id));
    }

    static function getUserById($db, $id) {
        $stmt = $db->prepare('
          SELECT UserID, Username, Password, Email, FirstName, LastName, Rank
          FROM Users 
          WHERE UserID = ? 
        ');

        $stmt->execute(array($id));
        $user = $stmt->fetch();
        if ($user) {
            return new User($user['UserID'], 
            $user['Username'], 
            $user['Password'], $user['Email'], 
            $user['FirstName'], $user['LastName'],
            $user['Rank']);
        } else {
            return null;
        }    
    }

    static function getUserByUsername($db, $username) {
        $stmt = $db->prepare('
            SELECT UserID, Username, Password, Email, FirstName, LastName, Rank
            FROM Users 
            WHERE LOWER(Username) = LOWER(?) 
        ');
    
        $stmt->execute(array($username));
        $user = $stmt->fetch();
        if ($user) {
            return new User($user['UserID'], $user['Username'], $user['Password'], $user['Email'], $user['FirstName'], $user['LastName'], $user['Rank']);
        } else {
            return null;
        }       
    }
    
    static function getUserByEmail($db, $email) {
        $stmt = $db->prepare('
            SELECT UserID, Username, Password, Email, FirstName, LastName, Rank
            FROM Users 
            WHERE Email = ? 
        ');
    
        $stmt->execute(array($email));
        $user = $stmt->fetch();
        if ($user) {
            return new User($user['UserID'], $user['Username'], $user['Password'], $user['Email'], $user['FirstName'], $user['LastName'], $user['Rank']);
        } else {
            return null;
        }       
    }
    public static function getAll($db) {
        $stmt = $db->prepare('
            SELECT * FROM Users
        ');
    
        $stmt->execute();
        $users = $stmt->fetchAll();
    
        $userObjects = array();
        foreach ($users as $user) {
            $userObjects[] = new User($user['UserID'], $user['Username'], $user['Password'], $user['Email'], $user['FirstName'], $user['LastName'], $user['Rank']);
        }
    
        return $userObjects;
    }
    public static function getUserWithPassword($db, $username, $password) {
        $stmt = $db->prepare('
            SELECT UserID, Username, Password, Email, FirstName, LastName, Rank
            FROM Users 
            WHERE Username = ? AND Password = ? 
        ');

        $stmt->execute(array($username, $password));
        $user = $stmt->fetch();
        if ($user) {
            return new User($user['UserID'], $user['Username'], $user['Password'], $user['Email'], $user['FirstName'], $user['LastName'], $user['Rank']);
        } else {
            return null;
        }       
    }

    public static function getUserWithPasswordEmail($db, $email, $password) {
        $stmt = $db->prepare('
            SELECT UserID, Username, Password, Email, FirstName, LastName, Rank
            FROM Users 
            WHERE Email = ? AND Password = ? 
        ');

        $stmt->execute(array($email, $password));
        $user = $stmt->fetch();
        if ($user) {
            return new User($user['UserID'], $user['Username'], $user['Password'], $user['Email'], $user['FirstName'], $user['LastName'], $user['Rank']);
        } else {
            return null;
        }       
    }


    public function promoteToAdmin($db){
        $stmt = $db->prepare('
            UPDATE Users SET Rank = 1 WHERE UserID = ?
        ');
    
        $stmt->execute(array($this->id));
    }
}
