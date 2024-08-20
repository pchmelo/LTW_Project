<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/product.class.php');

  $db = getDatabaseConnection();

  $products = Product::searchProducts($db, $_GET['search'], 8);
  $allProducts = Product::getAllProducts($db);
  $categories = Product::getAllCategories($db);
  $brands = Product::getAllBrands($db);
  $sizes = Product::getAllTamanhos($db);
  $models = Product::getAllModels($db);
  $conditions = Product::getAllConditions($db);


  $data = [
    'products' => $products,
    'categories' => $categories,
    'brands' => $brands,
    'sizes' => $sizes,
    'models' => $models,
    'conditions' => $conditions,
    'allProducts' => $allProducts,
  ];

  echo json_encode($data);
