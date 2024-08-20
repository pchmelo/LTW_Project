<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>


<!DOCTYPE html>
<html lang="en-US">
<link href="../css/profilePage.css" rel="stylesheet">
<link href="../css/landingPage.css" rel="stylesheet">
<script src="../javascript/searchbar.js" defer></script>


<head>
    <title>Profile</title>
    <meta charset="UTF-8">
</head>
        <?php function drawProfile($db, $session, $user){?>

        <div id="containerProfile">
        <section id="s1">
            <div>
                <img id="profilePic" src="../assets/placeholder-1-1.webp" id="imagemperfil" alt="<?= $user->name ?>">
                
                <!--<form method="post" enctype="multipart/form-data" action="../actions/action_upload.php">

                <input type="hidden" name="MAX_FILE_SIZE" value="1048576"> 

                <label for="image">Image file</label>
                <input type="file" id="image" name="image">

                <label for="file2">Another file</label>
                <input type="file" name="file2" id="file2">

<button>Upload</button>-->


            </div>
            <div id="info">
              
                <div id="flex1"><div id ="name"><?= $user->firstName?>  <?= $user->lastName?></div>
                <?php if($session->isLoggedIn() && $session->getId() == $user->id){ ?>
                <button id="Edit" onclick="location.href='profileSettings.php?username=<?=$user->username?>'">Settings</button></div>
                <?php } else { ?>
                <button id="DM">Message</button><button id="Share">Share Profile</button></div>
                <?php } ?>

                <p id="username">@<?= $user->username ?></p>
                <p id="numberproducts">Currently selling <?php echo Product::countProductsFromUser($db, $user->id)?> products</p>
            </div>
        </section></div>
        <?php } ?>

        <?php function drawSelling($session,$cartproducts, $sellingproducts, $db){?>
          <section id="productsGrid">
              <?php if(empty($sellingproducts)) { ?>
                  <h2></h2>
              <?php } else { ?>
                  <?php if($session->isLoggedIn() && $session->getId() == $sellingproducts[0]->SellerID){ ?>
                      <h2>Your Listed Products</h2>
                  <?php } else { ?>
                      <h2>Products from this user</h2>
                  <?php } ?>
                  <ul>
                  <?php foreach($sellingproducts as $sellingProduct) { ?>
                    <?php if ($sellingProduct->isAvailable == 1) { ?>
                      <li> 
                      <div id="productImage">
                          <img src="../<?= $sellingProduct->imageUrl ?>" alt="<?= $sellingProduct->name ?>">
                          <?php if ($sellingProduct->id != $session->getId()) { ?>
                            <?php
        if(!$session->isLoggedIn()) { ?>
            <button class="cartButtonLp" onclick="window.location.href='../pages/LogIn.php';">
                Log In to Add to Cart
            </button>
          <?php } else { ?>
            <?php if ($session->getId() != $sellingproducts[0]->SellerID) { ?>
              <button class="cartButtonLp"
                  data-productId='<?=$sellingProduct->id?>' 
                  data-sessionId='<?=$session->getId()?>' 
                  data-sellerId='<?=$sellingProduct->SellerID?>' 
                  data-isAvailable='<?=$sellingProduct->isAvailable?>'
                  <?php
                  $inCart = false;
                  foreach($cartproducts as $cartproduct) {
                      if($cartproduct->id == $sellingProduct->id) {
                          $inCart = true;
                          break;
                      }
                  }
                  if($inCart) echo 'disabled';
                  ?>>
                  <?php
                  echo $inCart ? "Already in your Cart" : "Add to Cart";
                  ?>
              </button>
              <?php } ?>
          <?php } ?>
                          
                          <?php } ?>
                        </div>
                          <div>
                          <h3><a id="productPage" href="../pages/product.php?id=<?= $sellingProduct->id?>" title="<?=$sellingProduct->name?>"><?= $sellingProduct->name ?></a></h3>
                          <p><?= $sellingProduct->description ?></p>
                          <p><?= $sellingProduct->price ?></p>
                          <p id="productSeller">@<a id="productSeller" href="../pages/profile.php?username=<?=user::getUserById($db, $sellingProduct->SellerID)->username?>"><?= (user::getUserById($db, $sellingProduct->SellerID))->username ?></a></p>
                          </div>
                      </li>
                    <?php } ?>
                  <?php } ?></ul>
        <?php } ?>
    </section>
<?php } ?>


</html>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    var element = document.querySelector('h1');
    var element2 = document.getElementById('searchInput');
    var element3 = document.getElementById('searchImg');
    var element4 = document.getElementById('nomeheader');
    var anuncio = document.getElementById('announce');
    anuncio.classList.add('scrolled');
    element3.style.display = 'block';
    element2.style.display = 'block';
    element.classList.add('scrolled');
    element4.classList.remove('name');
  });
</script>