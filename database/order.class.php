<?php

/*-- Orders Table
CREATE TABLE Orders (
  OrderID INTEGER PRIMARY KEY AUTOINCREMENT,
  ProductID INTEGER,
  BuyerID INTEGER,
  SellerID INTEGER, 
  OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  IsProcessed INTEGER DEFAULT 0,
  OrderGroup INTEGER,
  FOREIGN KEY (ProductID) REFERENCES Products(ProductID) ON DELETE NO ACTION ON UPDATE NO ACTION,
  FOREIGN KEY (BuyerID) REFERENCES Users(UserID) ON DELETE NO ACTION ON UPDATE NO ACTION,
  FOREIGN KEY (SellerID) REFERENCES Users(UserID) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT differentBuyerSeller CHECK (BuyerID <> SellerID)
);
);*/

declare(strict_types = 1);


class Order {
    public $orderID;
    public $productID;
    public $buyerID;
    public $sellerID;
    public $orderDate;
    public $isProcessed;
    public $orderGroup; 
    public $shipping;

    public function __construct($orderID, $productID, $buyerID, $sellerID, $orderDate, $isProcessed, $orderGroup, $shipping) {
        $this->orderID = $orderID;
        $this->productID = $productID;
        $this->buyerID = $buyerID;
        $this->sellerID = $sellerID;
        $this->orderDate = $orderDate;
        $this->isProcessed = $isProcessed;
        $this->orderGroup = $orderGroup; 
        $this->shipping = $shipping;
    }


    public function save($db) {
        $stmt = $db->prepare("INSERT INTO Orders (OrderID, ProductID, BuyerID, SellerID, OrderDate, IsProcessed, OrderGroup) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(array($this->orderID, $this->productID, $this->buyerID, $this->sellerID, $this->orderDate, $this->isProcessed, $this->orderGroup, $this->shipping));
    }

    public function update($db) {
        $stmt = $db->prepare("UPDATE Orders SET ProductID = ?, BuyerID = ?, SellerID = ?, OrderDate = ?, IsProcessed = ?, OrderGroup = ?, Shipping = ? WHERE OrderID = ?");
        $stmt->execute(array($this->productID, $this->buyerID, $this->sellerID, $this->orderDate, $this->isProcessed, $this->orderGroup, $this->shipping, $this->orderID));
    }
    
    public function delete($db) {
        $stmt = $db->prepare("DELETE FROM Orders WHERE OrderID = ?");
        $stmt->execute(array($this->orderID));
    }
    
    static function deleteFromCart($db, $productID, $buyerID) {
        $stmt = $db->prepare("DELETE FROM Orders WHERE ProductID = ? AND BuyerID = ?");
        $stmt->execute(array($productID, $buyerID));
    }



    public function create($db) {
        $stmt = $db->prepare('
            INSERT INTO Orders (ProductID, BuyerID, SellerID, OrderDate, IsProcessed, OrderGroup, Shipping) 
            VALUES (:productID, :buyerID, :sellerID, :orderDate, :isProcessed, :orderGroup, :shipping)
        ');
        
        $stmt->bindParam(':productID', $this->productID);
        $stmt->bindParam(':buyerID', $this->buyerID);
        $stmt->bindParam(':sellerID', $this->sellerID);
        $stmt->bindParam(':orderDate', $this->orderDate);
        $stmt->bindParam(':isProcessed', $this->isProcessed);
        $stmt->bindParam(':orderGroup', $this->orderGroup); 
        $stmt->bindParam(':shipping', $this->shipping);
        
        $stmt->execute();
    }
    
    public function process($db) {
        $stmt = $db->prepare('
            UPDATE Orders
            SET IsProcessed = 1, OrderGroup = :group, Shipping = :shipping
            WHERE BuyerID = :buyerID AND ProductID = :productID
        ');
        $stmt->bindParam(':buyerID', $this->buyerID);
        $stmt->bindParam(':productID', $this->productID);
        $stmt->bindParam(':group', $this->orderGroup);
        $stmt->bindParam(':shipping', $this->shipping);
        $stmt->execute();

        $stmt = $db->prepare('
            UPDATE Products
            SET isAvailable = 0
            WHERE ProductID = :productID
        ');
        $stmt->bindParam(':productID', $this->productID);
        $stmt->execute();
    }


    public static function getOrderByGroup($db, $group) {
        $stmt = $db->prepare('
            SELECT * FROM Orders
            WHERE OrderGroup = :group
        ');
        $stmt->bindParam(':group', $group);
        $stmt->execute();

        $orders = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = new Order($data['OrderID'], $data['ProductID'], $data['BuyerID'], $data['SellerID'], $data['OrderDate'], $data['IsProcessed'], $data['OrderGroup'], $data['Shipping']);
        }

        return $orders;
    }


    public static function getUnprocessedOrdersByBuyerID($db, $buyerID) {
        //error_reporting(E_ALL); 
        //var_dump($buyerID);
        $stmt = $db->prepare('
            SELECT DISTINCT Products.* FROM Orders
            INNER JOIN Products ON Orders.ProductID = Products.ProductID
            WHERE Orders.BuyerID = :buyerID AND Orders.IsProcessed = 0
        ');
    
        $stmt->bindParam(':buyerID', $buyerID);
        $stmt->execute();
    
        $products = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //var_dump($data);
    
            $products[] = new Product($data['ProductID'], 
            $data['productName'], 
            $data['Price'], 
            $data['Category'], 
            $data['Brand'], 
            $data['Model'], 
            $data['Tamanho'], 
            $data['Condition'], 
            $data['productDescription'], 
            $data['ImageURL'], 
            $data['SellerID'], 
            $data['isAvailable']);
        }
    
        //var_dump($products); 
    
        return $products;
    }

    public static function getProcessedOrdersByBuyerId($db, $buyerID) {
        $stmt = $db->prepare('
            SELECT DISTINCT Products.* FROM Orders
            INNER JOIN Products ON Orders.ProductID = Products.ProductID
            WHERE Orders.BuyerID = :buyerID AND Orders.IsProcessed = 1
        ');
    
        $stmt->bindParam(':buyerID', $buyerID);
        $stmt->execute();
    
        $products = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new Product($data['ProductID'], 
            $data['productName'], 
            $data['Price'], 
            $data['Category'], 
            $data['Brand'], 
            $data['Model'], 
            $data['Tamanho'], 
            $data['Condition'], 
            $data['productDescription'], 
            $data['ImageURL'], 
            $data['SellerID'], 
            $data['isAvailable']);
        }
    
        return $products;
    }

    public static function getProcessedOrdersBySellerId($db, $sellerID){
        $stmt = $db->prepare('
            SELECT DISTINCT Products.* FROM Orders
            INNER JOIN Products ON Orders.ProductID = Products.ProductID
            WHERE Orders.SellerID = :sellerID AND Orders.IsProcessed = 1
        ');
    
        $stmt->bindParam(':sellerID', $sellerID);
        $stmt->execute();
    
        $products = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new Product($data['ProductID'], 
            $data['productName'], 
            $data['Price'], 
            $data['Category'], 
            $data['Brand'], 
            $data['Model'], 
            $data['Tamanho'], 
            $data['Condition'], 
            $data['productDescription'], 
            $data['ImageURL'], 
            $data['SellerID'], 
            $data['isAvailable']);
        }
    
        return $products;
    }

    public static function getOrderGroupByProductID($db, $product) {
        $productID = $product->id;
    
        $stmt = $db->prepare('
            SELECT OrderGroup FROM Orders
            WHERE ProductID = :productID AND IsProcessed = 1
        ');
        $stmt->bindParam(':productID', $productID);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result['OrderGroup'];
    }
    
    public static function mapProductsToOrderGroups($db, $products) {
        $groupedProducts = [];
    
        foreach ($products as $product) {
            $orderGroup = self::getOrderGroupByProductID($db, $product);
    
            if (!isset($groupedProducts[$orderGroup])) {
                $groupedProducts[$orderGroup] = [];
            }
    
            $groupedProducts[$orderGroup][] = $product;
        }
    
        return $groupedProducts;
    }
    


    public static function getOrderBySellerIdAndProductId($db, $sellerID, $productID) {
        $stmt = $db->prepare("SELECT * FROM Orders WHERE SellerID = ? AND ProductID = ?");
        $stmt->execute(array($sellerID, $productID));
    
        $orderData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    
        if ($orderData) {
            return new Order($orderData['OrderID'], $orderData['ProductID'], $orderData['BuyerID'], $orderData['SellerID'], $orderData['OrderDate'], $orderData['IsProcessed'], $orderData['OrderGroup'], $orderData['Shipping']);
        } else {
            return null;
        }
    }


    public static function getHighestGroupValue($db) {
        $stmt = $db->prepare('
            SELECT MAX(OrderGroup) as maxGroup FROM Orders
        ');
        $stmt->execute();

        $groupData = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = null;

        if ($groupData) {
            return $groupData['maxGroup'];
        } else {
            return null;
        }
    }

    public static function getProcessedOrders($db){
        $stmt = $db->prepare('
            SELECT DISTINCT Products.* FROM Orders
            INNER JOIN Products ON Orders.ProductID = Products.ProductID
            WHERE Orders.IsProcessed = 1
        ');
    
        $stmt->execute();
    
        $products = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new Product($data['ProductID'], 
            $data['productName'], 
            $data['Price'], 
            $data['Category'], 
            $data['Brand'], 
            $data['Model'], 
            $data['Tamanho'], 
            $data['Condition'], 
            $data['productDescription'], 
            $data['ImageURL'], 
            $data['SellerID'], 
            $data['isAvailable']);
        }
    
        return $products;
    }


    public static function getBuyerIDByGroup($db, $group) {
        $stmt = $db->prepare('
            SELECT BuyerID FROM Orders
            WHERE OrderGroup = :group
        ');
        $stmt->bindParam(':group', $group);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['BuyerID'];
    }

}
