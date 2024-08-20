<?php

/*CREATE TABLE Products (
CREATE TABLE Products (
  ProductID INTEGER PRIMARY KEY AUTOINCREMENT,
  productName VARCHAR(100) NOT NULL,
  Price DECIMAL(10, 2) NOT NULL,
  Category VARCHAR(50),
  Brand VARCHAR(50),
  Model VARCHAR(50),
  Tamanho VARCHAR(50),
  Condition TEXT CHECK(Condition IN ('Excellent', 'Good', 'Average', 'Bad', 'Very Bad')),
  productDescription TEXT,
  ImageURL VARCHAR(200),
  SellerID INTEGER,
  isAvailable INTEGER DEFAULT 1,  
  FOREIGN KEY (SellerID) REFERENCES Users(UserID) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT name_length_check CHECK (LENGTH(productName) >= 2)
);
);*/

declare(strict_types = 1);

class Product {
    public $id;
    public $name;
    public $price;
    public $category;
    public $brand;
    public $model;
    public $tamanho;
    public $condition;
    public $description;
    public $imageUrl;
    public $SellerID;
    public $isAvailable;

    public function __construct($id, $name, $price, $category, $brand, $model, $tamanho, $condition, $description, $imageUrl, $SellerID, $isAvailable) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
        $this->brand = $brand;
        $this->model = $model;
        $this->tamanho = $tamanho;
        $this->condition = $condition;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->SellerID = $SellerID;
        $this->isAvailable = $isAvailable;
    }

    public function save($db) {
        $stmt = $db->prepare('
        UPDATE Products SET productName = ?, Price = ?, Category = ?, Brand = ?, Model = ?, Tamanho = ?, Condition = ?, productDescription = ?, ImageUrl = ?, SellerID = ?, isAvailable = ? 
        WHERE ProductID = ?
      ');
    
        $stmt->execute(array($this->name, $this->price, $this->category, $this->brand, $this->model, $this->tamanho, $this->condition, $this->description, $this->imageUrl, $this->SellerID, $this->isAvailable, $this->id));
    }

    public function create($db) {
        $stmt = $db->prepare('
            INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageUrl, SellerID, isAvailable) 
            VALUES (:productName, :price, :category, :brand, :model, :tamanho, :condition, :description, :imageUrl, :sellerId, :isAvailable)
        ');
    
        $stmt->bindParam(':productName', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':brand', $this->brand);
        $stmt->bindParam(':model', $this->model);
        $stmt->bindParam(':tamanho', $this->tamanho);
        $stmt->bindParam(':condition', $this->condition);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':imageUrl', $this->imageUrl);
        $stmt->bindParam(':sellerId', $this->SellerID);
        $stmt->bindParam(':isAvailable', $this->isAvailable); 
    
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "PDOException: " . $e->getMessage();
            if ($e->errorInfo[1] == 19) {
                throw new Exception('The product already exists');
            } else {
                throw $e;
            }
        }
    }



    static function getProductById($db, $id) {
        $stmt = $db->prepare('
        SELECT ProductID, productName, Price, Category, Brand, Model, 
        Tamanho, Condition, productDescription, ImageUrl, 
        SellerID, isAvailable FROM Products 
          WHERE ProductID = ?
        ');
    
        $stmt->execute(array($id));
        $product = $stmt->fetch();
    
        if ($product === false) {
            return null;
        }
    
        return new Product(
          $product['ProductID'],
          $product['productName'],
          $product['Price'],
          $product['Category'],
          $product['Brand'],
          $product['Model'],
          $product['Tamanho'],
          $product['Condition'],
          $product['productDescription'],
          $product['ImageURL'],
          $product['SellerID'],
          $product['isAvailable']
        );
    }
    
public function delete($db) {
    $stmt = $db->prepare('
      DELETE FROM Products WHERE ProductID = ?');
    $stmt->execute(array($this->id));
}


static function getProducts(PDO $db, int $count) : array {
        $stmt = $db->prepare('SELECT 
        ProductID, productName, Price, Category, Brand, Model, 
        Tamanho, Condition, productDescription, ImageUrl, SellerID, isAvailable
        FROM Products LIMIT ?');
        $stmt->execute(array($count));
    
        $products = array();
        while ($product = $stmt->fetch()) {
            $products[] = new Product(
                $product['ProductID'],
                $product['productName'],
                $product['Price'],
                $product['Category'],
                $product['Brand'],
                $product['Model'],
                $product['Tamanho'],
                $product['Condition'],
                $product['productDescription'],
                $product['ImageURL'],
                $product['SellerID'],
                $product['isAvailable']
            );
        }
        
      return $products;
}


public static function getProductsFromUser($db, $id) : array{
      $stmt = $db->prepare('
      SELECT * FROM PRODUCTS
      WHERE SellerID = ?
      ');
      
      $stmt->execute(array($id));

      $products = array();
      while ($product = $stmt->fetch()) {
        $products[] = new Product(
          $product['ProductID'],
          $product['productName'],
          $product['Price'],
          $product['Category'],
          $product['Brand'],
          $product['Model'],
          $product['Tamanho'],
          $product['Condition'],
          $product['productDescription'],
          $product['ImageURL'],
          $product['SellerID'],
          $product['isAvailable']
        );
      }
      
      return $products;
  }

  public static function countProductsFromUser($db, $id) : int{
    $stmt = $db->prepare('
    SELECT COUNT(*) FROM PRODUCTS
    WHERE SellerID = ? AND isAvailable = 1
    ');
    
    $stmt->execute(array($id));
    return $stmt->
    fetchColumn();
  }


  public static function getProductsFromCategory($db, $category) : array{
    $stmt = $db->prepare('
    SELECT * FROM PRODUCTS
    WHERE Category = ? AND isAvailable = 1
    ');
    
    $stmt->execute(array($category));

    $products = array();
    while ($product = $stmt->fetch()) {
      $products[] = new Product(
        $product['ProductID'],
        $product['productName'],
        $product['Price'],
        $product['Category'],
        $product['Brand'],
        $product['Model'],
        $product['Tamanho'],
        $product['Condition'],
        $product['productDescription'],
        $product['ImageURL'],
        $product['SellerID'],
        $product['isAvailable']
      );
    }
    
    return $products;
}

static function searchProducts(PDO $db, string $search, int $count) : array {

  $stmt = $db->prepare('SELECT * FROM Products WHERE productName LIKE ? LIMIT ?');  $stmt->bindValue(1, $search . '%', PDO::PARAM_STR);
  $stmt->bindValue(2, $count, PDO::PARAM_INT);
  $stmt->execute();

  $products = array();
  while ($product = $stmt->fetch()) {
    $products[] = new Product(
      $product['ProductID'],
      $product['productName'],
      $product['Price'],
      $product['Category'],
      $product['Brand'],
      $product['Model'],
      $product['Tamanho'],
      $product['Condition'],
      $product['productDescription'],
      $product['ImageURL'],
      $product['SellerID'],
      $product['isAvailable']
    );
  }

  return $products;
}


public static function getAllCategories(PDO $db) : array {
    $stmt = $db->prepare('SELECT DISTINCT Category FROM Products');
    $stmt->execute();

    $categories = array();
    while ($category = $stmt->fetch()) {
        $categories[] = $category['Category'];
    }

    return $categories;
}

public static function getAllTamanhos(PDO $db) : array {
  $stmt = $db->prepare('SELECT DISTINCT Tamanho FROM Products');
  $stmt->execute();

  $tamanhos = array();
  while ($tamanho = $stmt->fetch()) {
      $tamanhos[] = $tamanho['Tamanho'];
  }

  return $tamanhos;
}

public static function getAllBrands(PDO $db) : array {
    $stmt = $db->prepare('SELECT DISTINCT Brand FROM Products');
    $stmt->execute();

    $brands = array();
    while ($brand = $stmt->fetch()) {
        $brands[] = $brand['Brand'];
    }

    return $brands;
}

public static function getAllModels(PDO $db) : array {
  $stmt = $db->prepare('SELECT DISTINCT Model FROM Products');
  $stmt->execute();

  $models = array();
  while ($model = $stmt->fetch()) {
      $models[] = $model['Model'];
  }

  return $models;
}

public static function getAllConditions(PDO $db) : array {
    $stmt = $db->prepare('SELECT DISTINCT Condition FROM Products');
    $stmt->execute();

    $conditions = array();
    while ($condition = $stmt->fetch()) {
        $conditions[] = $condition['Condition'];
    }

    return $conditions;
}

static function getAllProducts(PDO $db) : array {
    $stmt = $db->prepare('SELECT 
    ProductID, productName, Price, Category, Brand, Model, 
    Tamanho, Condition, productDescription, ImageUrl, SellerID, isAvailable
    FROM Products');
    $stmt->execute();
    
    $products = array();
    while ($product = $stmt->fetch()) {
        $products[] = new Product(
            $product['ProductID'],
            $product['productName'],
            $product['Price'],
            $product['Category'],
            $product['Brand'],
            $product['Model'],
            $product['Tamanho'],
            $product['Condition'],
            $product['productDescription'],
            $product['ImageURL'],
            $product['SellerID'],
            $product['isAvailable']
        );
    }
    
    return $products;
}


static function getAvailableProducts(PDO $db) : array {
    $stmt = $db->prepare('
    SELECT ProductID, productName, Price, Category, Brand, Model, 
    Tamanho, Condition, productDescription, ImageUrl, SellerID, isAvailable 
    FROM Products 
    WHERE isAvailable = 1
    ');

    $stmt->execute();
    
    $products = [];
    while ($product = $stmt->fetch()) {
        $products[] = new Product(
            $product['ProductID'],
            $product['productName'],
            $product['Price'],
            $product['Category'],
            $product['Brand'],
            $product['Model'],
            $product['Tamanho'],
            $product['Condition'],
            $product['productDescription'],
            $product['ImageURL'],
            $product['SellerID'],
            $product['isAvailable']
        );
    }
    return $products;
}

static function findNextAvailableProduct(PDO $db, int $id) : ?Product {
    $SellerID = Product::getProductById($db, $id)->SellerID;
    $stmt = $db->prepare('
    SELECT *
    FROM Products 
    WHERE ProductID > ? AND isAvailable = 1 AND SellerID = ?
    ORDER BY ProductID ASC
    LIMIT 1
    ');

    $stmt->execute(array($id, $SellerID));
    $product = $stmt->fetch();

    if ($product === false) {
        return null;
    }

    return new Product(
        $product['ProductID'],
        $product['productName'],
        $product['Price'],
        $product['Category'],
        $product['Brand'],
        $product['Model'],
        $product['Tamanho'],
        $product['Condition'],
        $product['productDescription'],
        $product['ImageURL'],
        $product['SellerID'],
        $product['isAvailable']
    );
}



static function findPreviousAvailableProduct(PDO $db, int $id) : ?Product {
    $SellerID = Product::getProductById($db, $id)->SellerID;
    $stmt = $db->prepare('
    SELECT *
    FROM Products 
    WHERE ProductID < ? AND isAvailable = 1 AND SellerID = ?
    ORDER BY ProductID DESC
    LIMIT 1
    ');

    $stmt->execute(array($id,$SellerID));
    $product = $stmt->fetch();

    if ($product === false) {
        return null;
    }

    return new Product(
        $product['ProductID'],
        $product['productName'],
        $product['Price'],
        $product['Category'],
        $product['Brand'],
        $product['Model'],
        $product['Tamanho'],
        $product['Condition'],
        $product['productDescription'],
        $product['ImageURL'],
        $product['SellerID'],
        $product['isAvailable']
    );
}

};


