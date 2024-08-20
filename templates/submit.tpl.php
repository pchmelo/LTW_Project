<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>


<!DOCTYPE html>
<html lang="en-US">
<link href="../css/profilePage.css" rel="stylesheet">
<link href="../css/landingPage.css" rel="stylesheet">
<link href="../css/searchPage.css" rel="stylesheet">
<link href="../css/submitProduct.css" rel="stylesheet">
<script src="../javascript/search.js" defer></script>
<script src="../javascript/searchbar.js" defer></script>
<script src="../javascript/upload.js"></script>





<head>
    <title>Search</title>
    <meta charset="UTF-8">
</head>


<?php
function drawSellingForm($user, $categories, $conditions, $tamanhos, $errors, $session){ 
?>

<div id="SubmitProductContainer">
    <div class="submit-section">
        <div class="submit-info">
            <h1>Sell a Product</h1>

            <?php if (!empty($errors)): ?>
                <div class="errors">
                    <?php foreach ($errors as $error): ?>
                        <p><em><?php echo $error; ?></em></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form id="uploadForm" method="POST" action="../actions/action_upload.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
                <div id="infoItem" class="info-item">
                    <label id="imageLabel" for="image">Product Image</label>
                    <input style="display: none;" type="file" id="image" name="image">
                    <label for="image" class="uploadButton">Upload</label>
                    <button style="display:none" id="uploadButton" class="uploadButton" type="submit">Upload</button>
                </div>
            </form>

            <form method="POST" action="../actions/action_submitProduct.php">
                <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
                <div class="info-item">
                    <label for="name">NAME</label>
                    <div class="relativeCounterContainer">
                        <input type="text" id="name" name="name" maxlength="50" oninput="updateCounter('name', 'nameCounter', 50)">
                        <p id="nameCounter" class="counter">0 / 50</p>
                    </div>
                </div>
                <div class="info-item">
                    <label for="price">PRICE ($)</label>
                    <input type="number" id="price" name="price" step="0.01">
                </div>
                <div class="info-item">
                    <label for="category">CATEGORY</label>
                    <select id="category" name="category">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo str_replace(' ', '', $category); ?>"><?php echo $category; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="info-item">
                    <label for="brand">BRAND</label>
                    <div class="relativeCounterContainer">
                        <input type="text" id="brand" name="brand" maxlength="50" oninput="updateCounter('brand', 'brandCounter', 50)">
                        <p id="brandCounter" class="counter">0 / 50</p>
                    </div>
                </div>
                <div class="info-item">
                    <label for="model">MODEL</label>
                    <div class="relativeCounterContainer">
                        <input type="text" id="model" name="model" maxlength="50" oninput="updateCounter('model', 'modelCounter', 50)">
                        <p id="modelCounter" class="counter">0 / 50</p>
                    </div>
                </div>
                <div class="info-item">
                    <label for="size">SIZE</label>
                    <div class="relativeCounterContainer">
                    <select id="size" name="size">
                        <?php foreach ($tamanhos as $tamanho): ?>
                            <option value="<?php echo str_replace(' ', '', $tamanho); ?>"><?php echo $tamanho; ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>
                <div class="info-item">
                    <label for="condition">CONDITION</label>
                    <select id="condition" name="condition">
                        <?php foreach ($conditions as $condition): ?>
                            <option value="<?php echo $condition; ?>"><?php echo $condition; ?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
                <div class="info-item">
                    <label for="description">DESCRIPTION</label>
                    <div class="relativeCounterContainer">
                        <textarea id="description" maxlength="500" name="description" oninput="updateCounter('description', 'descriptionCounter', 500)"></textarea>
                        <p id="descriptionCounter" class="counter">0 / 500</p>
                    </div>
                </div>
                <input type="hidden" id="SellerID" name="SellerID" value="<?php echo $user->id; ?>">
                <input type="hidden" id="imageUrl" name="imageUrl">
                
                <button type="submit">SUBMIT</button>
            </form>
        </div>
    </div>
</div>

<?php 
} 
?>




<script>
    
    enableUploads();

document.addEventListener('DOMContentLoaded', function() {
    var element = document.querySelector('h1');
    var element2 = document.getElementById('searchInput');
    var element3 = document.getElementById('searchImg');
    var element4 = document.getElementById('nomeheader');

    element3.style.display = 'block';
    element2.style.display = 'block';
    element.classList.add('scrolled');
    element4.classList.remove('name');
  });


function updateCounter(inputId, counterId, maxLength) {
    var currentLength = document.getElementById(inputId).value.length;
    document.getElementById(counterId).innerText = currentLength + ' / ' + maxLength;
}

  </script>