DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Products;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Comments;
DROP TABLE IF EXISTS Replies;
DROP TABLE IF EXISTS Characteristics;


-- Users Table
CREATE TABLE Users (
  UserID INTEGER PRIMARY KEY AUTOINCREMENT,
  Username VARCHAR(50) UNIQUE NOT NULL,
  Password VARCHAR(100) NOT NULL, 
  Email VARCHAR(100) UNIQUE NOT NULL,
  FirstName VARCHAR(50),
  LastName VARCHAR(50),
  ProfilePicURL VARCHAR(200),
  Rank INTEGER DEFAULT 0,
  CONSTRAINT usernameLengthCheck CHECK (LENGTH(Username) >= 2), 
  CONSTRAINT emailLengthCheck CHECK (LENGTH(Email) >= 11)
);


-- Products Table
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


-- Comments Table
CREATE TABLE Comments (
  CommentID INTEGER PRIMARY KEY AUTOINCREMENT,
  ProductID INTEGER,
  AuthorID INTEGER,
  Content TEXT,
  FOREIGN KEY (ProductID) REFERENCES Products(ProductID) ON DELETE CASCADE ON UPDATE NO ACTION,
  FOREIGN KEY (AuthorID) REFERENCES Users(UserID) ON DELETE CASCADE ON UPDATE NO ACTION
);

CREATE TABLE Replies (
  ReplyID INTEGER PRIMARY KEY AUTOINCREMENT,
  CommentID INTEGER,
  AuthorID INTEGER,
  ReplyText TEXT,
  FOREIGN KEY (CommentID) REFERENCES Comments(CommentID) ON DELETE CASCADE ON UPDATE NO ACTION,
  FOREIGN KEY (AuthorID) REFERENCES Users(UserID) ON DELETE CASCADE ON UPDATE NO ACTION
);




-- Orders Table
CREATE TABLE Orders (
  OrderID INTEGER PRIMARY KEY AUTOINCREMENT,
  ProductID INTEGER,
  BuyerID INTEGER,
  SellerID INTEGER, 
  OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  IsProcessed INTEGER DEFAULT 0,
  OrderGroup INTEGER,
  Shipping INTEGER DEFAULT 0,
  FOREIGN KEY (ProductID) REFERENCES Products(ProductID) ON DELETE NO ACTION ON UPDATE NO ACTION,
  FOREIGN KEY (BuyerID) REFERENCES Users(UserID) ON DELETE NO ACTION ON UPDATE NO ACTION,
  FOREIGN KEY (SellerID) REFERENCES Users(UserID) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT differentBuyerSeller CHECK (BuyerID <> SellerID)
);

CREATE TABLE Characteristics(
  CharacteristicsID INTEGER PRIMARY KEY AUTOINCREMENT,
  CharacteristicsType VARCHAR NOT NULL,
  CharacteristicsValue VARCHAR NOT NULL,
  CharacteristicsImg VARCHAR NOT NULL
);

CREATE INDEX idx_orders_buyer ON Orders(BuyerID);
CREATE INDEX idx_orders_seller ON Orders(SellerID);
CREATE INDEX idx_orders_product ON Orders(ProductID);
CREATE INDEX idx_products_seller ON Products(SellerID);


-- All passwords are 1234 in SHA-1 format

-- Users

--password:Admin123 
insert into Users (Username, Password, Email, FirstName, LastName, Rank) 
values('admin', '$2y$10$Mc8.3JE4W8mdw63ceyvpTuQxwRGWquRvJN1sBKNiHFxeh.7eft4ZK', 'rascocunhelo@gmail.com', 'Vaskel', 'Melunha', 1);

--password:Jjoker2004CNT 
insert into Users (Username, Password, Email, FirstName, LastName, Rank) 
values('Crazy', '$2y$10$quqrjGcbjyirx5451Jjm/eA6d88RnS94viCDopcOWfdH3TZqm4Li.', 'sapinho@gmail.com', 'Crazy', 'Frog', 0);

--password:QImenos15
insert into Users (Username, Password, Email, FirstName, LastName, Rank) 
values('Rui', '$2y$10$7qdfcVkZVx1sYXojhA6RueHbaLMAYiv9b/6eiiAyzq632lT.ewAMq', 'fsala@gmail.com', 'Rui', 'Ferreira', 0);

--password:Gabriel123
insert into Users (Username, Password, Email, FirstName, LastName, Rank) 
values('Gabriel Carvalho', '$2y$10$17vgyw.b/tWmIGrfL67gBeDcLZZBadMVDLKsYT4At9MTQn8hhQwgG', 'gcfc44@xbox.jp', 'Gabriel', 'Carvalho', 0);

--password:Gabriel123
insert into Users (Username, Password, Email, FirstName, LastName, Rank) 
values('Wúl10', '$2y$10$17vgyw.b/tWmIGrfL67gBeDcLZZBadMVDLKsYT4At9MTQn8hhQwgG', 'ninjaGaia@outlook.com', 'Ninja', 'Gaia', 0);

--password:Gabriel123
insert into Users (Username, Password, Email, FirstName, LastName, Rank) 
values('Celorica', '$2y$10$17vgyw.b/tWmIGrfL67gBeDcLZZBadMVDLKsYT4At9MTQn8hhQwgG', 'marcelo10@iol.pt', 'Mr', 'President', 0);


--Products

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Apple iPhone 12', '799.99', 'Smartphone', 'Apple', 'iPhone 12', '6.1"', 'Excellent', 'The latest iPhone model from Apple', 'assets/uploads/iphone12.webp', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Samsung Galaxy S21', '899.99', 'Smartphone', 'Samsung', 'Galaxy S21', '6.2"', 'Good', 'The latest Galaxy model from Samsung', 'assets/uploads/samsungS21.webp', '2', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Google Pixel 5', '699.99', 'Smartphone', 'Google', 'Pixel 5', '6.0"', 'Average', 'The latest Pixel model from Google', 'assets/uploads/pixel5.webp', '3', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('OnePlus 9 Pro', '999.99', 'Smartphone', 'OnePlus', '9 Pro', '6.7"', 'Bad', 'The latest OnePlus model', 'assets/uploads/onePlus9.jpg', '4', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Xiaomi Mi 11', '799.99', 'Smartphone', 'Xiaomi', 'Mi 11', '6.81"', 'Very Bad', 'The latest Xiaomi model', 'assets/uploads/xiaomi11.jpg', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Sony Xperia 1 III', '1099.99', 'Smartphone', 'Sony', 'Xperia 1 III', '6.5"', 'Excellent', 'The latest Sony Xperia model', 'assets/uploads/sonyXperia.jpg', '2', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('LG Velvet', '599.99', 'Smartphone', 'LG', 'Velvet', '6.8"', 'Good', 'The latest LG model', 'assets/uploads/lgVelvet.jpg', '3', 1);

-- CPUs
INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Intel Core i9', '499.99', 'CPU', 'Intel', 'Core i9', 'N/A', 'Excellent', 'High performance CPU from Intel', 'assets/uploads/i9.webp', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('AMD Ryzen 9', '449.99', 'CPU', 'AMD', 'Ryzen 9', 'N/A', 'Excellent', 'High performance CPU from AMD', 'assets/uploads/amdRyzen.webp', '2', 1);

-- GPUs
INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('NVIDIA GeForce RTX 3080', '699.99', 'GPU', 'NVIDIA', 'RTX 3080', 'N/A', 'Excellent', 'High performance GPU from NVIDIA', 'assets/uploads/rtx3080.jpg', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('AMD Radeon RX 6800 XT', '649.99', 'GPU', 'AMD', 'RX 6800 XT', 'N/A', 'Excellent', 'High performance GPU from AMD', 'assets/uploads/amdRadeon.webp', '2', 1);

-- Computers
INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Dell XPS 15', '1599.99', 'Computer', 'Dell', 'XPS 15', 'N/A', 'Excellent', 'High performance laptop from Dell', 'assets/uploads/dellXPS.jpg', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Apple MacBook Pro', '1899.99', 'Computer', 'Apple', 'MacBook Pro', 'N/A', 'Excellent', 'High performance laptop from Apple', 'assets/uploads/appleMacBookPro.jpeg', '2', 1);

-- Mouses
INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Logitech MX Master 3', '99.99', 'Mouse', 'Logitech', 'MX Master 3', 'N/A', 'Excellent', 'High performance mouse from Logitech', 'assets/uploads/logitechMX.jpeg', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Razer DeathAdder V2', '69.99', 'Mouse', 'Razer', 'DeathAdder V2', 'N/A', 'Excellent', 'High performance mouse from Razer', 'assets/uploads/RazerDeathAdderV2.webp', '2', 1);

-- Keyboards
INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Corsair K95 RGB Platinum', '199.99', 'Keyboard', 'Corsair', 'K95 RGB Platinum', 'N/A', 'Excellent', 'High performance keyboard from Corsair', 'assets/uploads/corsair-k95-rgb-platinum-xt-featured.jpg', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Logitech G Pro X', '129.99', 'Keyboard', 'Logitech', 'G Pro X', 'N/A', 'Excellent', 'High performance keyboard from Logitech', 'assets/uploads/Logitech-Angle-2.webp', '2', 1);

-- Monitors
INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Dell Ultrasharp U2720Q', '579.99', 'Monitor', 'Dell', 'Ultrasharp U2720Q', '27"', 'Excellent', 'High performance monitor from Dell', 'assets/uploads/DellUltrasharpU2720Q.jpg', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('LG 27UK850-W', '449.99', 'Monitor', 'LG', '27UK850-W', '27"', 'Excellent', 'High performance monitor from LG', 'assets/uploads/LG27UK850-W.webp', '2', 1);

-- RAM
INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Corsair Vengeance LPX 16GB', '79.99', 'RAM', 'Corsair', 'Vengeance LPX', '16GB', 'Excellent', 'High performance RAM from Corsair', 'assets/uploads/CorsairVengeanceLPX16GB.jpg', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Kingston HyperX Fury 16GB', '74.99', 'RAM', 'Kingston', 'HyperX Fury', '16GB', 'Excellent', 'High performance RAM from Kingston', 'assets/uploads/KingstonHyperXFury16GB.jpg', '2', 1);

-- SSDs
INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Samsung 970 EVO Plus 1TB', '149.99', 'SSD', 'Samsung', '970 EVO Plus', '1TB', 'Excellent', 'High performance SSD from Samsung', 'assets/uploads/Samsung970EVOPlus1TB.jpg', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Crucial MX500 1TB', '99.99', 'SSD', 'Crucial', 'MX500', '1TB', 'Excellent', 'High performance SSD from Crucial', 'assets/uploads/CrucialMX5001TB.webp', '2', 1);

-- HDDs
INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Seagate BarraCuda 2TB', '54.99', 'HDD', 'Seagate', 'BarraCuda', '2TB', 'Excellent', 'High performance HDD from Seagate', 'assets/uploads/disco-rigido-35.jpg', '1', 1);

INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Western Digital Blue 2TB', '49.99', 'HDD', 'Western Digital', 'Blue', '2TB', 'Excellent', 'High performance HDD from Western Digital', 'assets/uploads/wd_blue_6_1.jpg', '2', 1);

-- Power Supplies
INSERT INTO Products (productName, Price, Category, Brand, Model, Tamanho, Condition, productDescription, ImageURL, SellerID, isAvailable) 
VALUES ('Corsair RM850x', '134.99', 'Power Supply', 'Corsair', 'RM850x', '850W', 'Excellent', 'High performance power supply from Corsair', 'assets/uploads/corsairRM850x.jpg', '1', 1);


-- Categorias
INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'Smartphone', 'assets/uploads/smartphones.jpg');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'CPU', 'assets/uploads/cpu.jpg');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'GPU', 'assets/uploads/gpu.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'Computer', "assets/uploads/computers.jpg");

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'Mouse', 'assets/uploads/mouses.jpeg');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'Keyboard', 'assets/uploads/keyboards.jpg');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'Monitor', 'assets/uploads/monitores.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'RAM', 'assets/uploads/rams.jpg');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'SSD', 'assets/uploads/ssd.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'HDD', 'assets/uploads/hdd.png');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Categories', 'Power Supply', 'assets/uploads/power_supply.webp');

-- Tamanho
INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '6.1"', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '6.2"', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '6.0"', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '6.7"', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '6.81"', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '6.5"', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '6.8"', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', 'N/A', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '27"', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '16GB', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '1TB', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '2TB', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Tamanho', '850W', 'assets/placeholder-1-1.webp');

-- Condition
INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Condition', 'Excellent', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Condition', 'Good', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Condition', 'Average', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Condition', 'Bad', 'assets/placeholder-1-1.webp');

INSERT INTO Characteristics (CharacteristicsType, CharacteristicsValue, CharacteristicsImg)
VALUES ('Condition', 'Very Bad', 'assets/placeholder-1-1.webp');




