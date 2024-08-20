<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>


<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>LandingPage</title>
    <meta charset="UTF-8">
    <link href="../css/landingPage.css" rel="stylesheet">
    <script src="../javascript/sidemenu.js" defer></script>
    <script src="../javascript/addToCart.js" defer></script>


</head>
    

<?php 
function drawHeader($db, Session $session) {  ?>
<body>

<header id="header">
      <h1 id="fixedHeader"><div><input type="checkbox" id="hamburger">
      <label class="hamburger" for="hamburger"></label>
      <a href="../index.php"><div class="name" id="nomeheader">Vintech</div></a></div>
      <div class="left-header"><div class="header-left" id="search"><input type="text" id = "searchInput" placeholder="Search Products...">
      <div class="searchImgContainer">
      <img src="/assets/search-svgrepo-com.svg" id="searchImg"></div></div>
      <div class="left-header">
        <?php if ($session->isLoggedIn()) { ?>
            <button id="announce" 
            onclick="window.location.href='../pages/productSubmit.php?username=<?=User::getUserById($db,$session->getId())->username?>'">
            Sell a Product</button>
            <div class="header-left"
            <?php
            $user = user::getUserById($db, $session->getId());
            $username = $user->username;
            ?>
            onclick="window.location.href='../pages/cart.php?username=<?=$username?>'"
            ><img src="/assets/shopcart.svg"></div>

            <div class="header-left" id="perfil">
            <?php
                $user = user::getUserById($db, $session->getId());
                $username = $user->username;
                ?>
                <a href="../pages/profile.php?username=<?=$username?>">
                <img src="/assets/profile-1341-svgrepo-com.svg"></a></div>
        </div>
        <?php } else {  ?>
            <?php
            $username = 0;
            ?>
            <button id="announce"
                        onclick="window.location.href='../pages/productSubmit.php?username=<?=$username?>'">
                        Sell a Product</button>
            <div class="header-left"
            onclick="window.location.href='../pages/LogIn.php'"
            ><img src="/assets/shopcart.svg"></div>
            <div class="header-left" id="perfil"><a href="../pages/LogIn.php"><img src="/assets/profile-1341-svgrepo-com.svg"></a></div>
                                            </div>
        <?php } ?>
        </h1>
        <?php if ($session->isLoggedIn()) { ?>
            <div class="menu-lateral">
                <ul>
                    <?php
                    $user = user::getUserById($db, $session->getId());
                    $username = $user->username;
                    ?>
                    <li><a href="../pages/profile.php?username=<?=$username?>"><button id="profile">Profile</button></a></li>
                    <?php if(User::getUserById($db,$session->getId())->rank == 1) { ?>
                        <li><a href="../pages/admin.php?selector=1"><button class="adminButton" id="admin">Admin</button></a></li>
                    <?php } ?>
                    <li><a href="../actions/action_logout.php"><button id="LogOut">Log Out</button></a></li>
                    

                </ul>
            </div>
        <?php } else {  ?>
            <div class="menu-lateral">
                <ul>
                    <li><a href="../pages/SignUp.php"><button id="register">Sign Up</button></a></li>
                    <li><a href="../pages/LogIn.php"><button id="LogIn">Log In</button></a></li>
                </ul>
            </div>
        <?php } ?>
</div>



      </h2>
    </header>
    <div class="container" id="container">
<?php } ?>

<?php function heroSection($session) { ?>
<header>
        <div class="hero">
            <div class="hero-text">
            <h1>Welcome to Vintech Marketplace</h1>
            <p>Discover quality second-hand tech and give your unused tech a new life.</p>
            </div>
            <input type="text" id="hero-input" class="hero-input" placeholder="Enter the desired Items for search">
        </div>
    </header>
<?php } ?>

<?php function cathegorySection($session, $products, $categories, $db) { ?>
    <main>
        <section id="cathegory">
            <h1>Choose your desired category</h1>
            <div>
            <ul>
            <?php $categorias = Caracteristicas::getCaracteristicasByType($db,'Categories') ?>
                <?php foreach($categories as $category): ?>
                    <?php foreach($categorias as $categoriaCarac){
                        if($categoriaCarac->caracValue == $category){
                            $imgUrl = $categoriaCarac->caracImg;
                        }
                    }
                    ?>
                <li onclick="window.location='../pages/search.php?search=&category=<?php echo $category; ?>'">
                    <a href="../pages/search.php?search=&category=<?php echo $category; ?>">
                        <img src="../<?php echo $imgUrl?>" alt="placeholder">
                        <h2><?php echo $category; ?></h2>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            </div>
        </section>
    </main>
<?php } ?>

<?php function productsGrid($session, $cartproducts, array $products,PDO $db) {
    $counter = 0; ?>
    <section id="productsGrid">
    <h2><div>Products</div></h2>

    <ul>
        <?php foreach($products as $product){ if ($product->SellerID != $session->getId()) { ?>
            <?php $counter++; ?>
            <?php if($counter > 10) { ?>
                <li style="display: none">
            <?php } else { ?>
                <li> 
            <?php } ?> 
            
                <div id="productImage">
                    <img src="<?= $product->imageUrl ?>" alt="<?= $product->name ?>">
                    <?php
            if(!$session->isLoggedIn()) { ?>
                <button class="cartButtonLp" onclick="window.location.href='../pages/LogIn.php';">
                    Log In to Add to Cart
                </button>
            <?php } else { ?>
                <button class="cartButtonLp"
                    data-productId='<?=$product->id?>' 
                    data-sessionId='<?=$session->getId()?>' 
                    data-sellerId='<?=$product->SellerID?>' 
                    data-isAvailable='<?=$product->isAvailable?>'
                    <?php
                    $inCart = false;
                    foreach($cartproducts as $cartproduct) {
                        if($cartproduct->id == $product->id) {
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
                </div>
                <div id="productSeller">@<a id="productSeller" href="pages/profile.php?username=<?=(user::getUserById($db, $product->SellerID))->username?>"><?= (user::getUserById($db, $product->SellerID))->username ?></a></div>
                <div>
                    <div class="h3"><a id="productPage" href="pages/product.php?id=<?= $product->id?>" title="<?=$product->name?>"><?= $product->name ?></a></div>
                    <p class="description"><?= $product->description ?></p>
                    <p>Condition: <?= $product->condition ?></p>
                    <p class = "priceInGrid">$<?= $product->price ?></p>
                </div>
            </li>
        <?php } ?>
    <?php } ?>
    </ul>
    <button id="loadMore" onclick=loadMore()>Load more products</button>
</section>

    <footer>
    </footer>

    <?php } ?>

    <?php
function drawFooter($db, $session){ ?> 
    <footer id="footer">
        <div class="footer-section">
            <h2 id="name">Vintech</h2>
            <p id="catchphrase">The Best Online Technology Marketplace</p>
        </div>
        <div class="footer-section">
            <h3 id="footer-categories"></h3>
        </div>
        <div class="footer-section">
            <div class="footer-links">
                <?php
                if($session->isLoggedIn()) { ?>
                    <a href="../pages/profile.php?username=<?=(user::getUserById($db, $session->getId()))->username?>">Profile</a>
                    <a href="../actions/action_logout.php">Log Out</a>
                <?php } else { ?>
                    <a href="../pages/SignUp.php">SignUp</a>
                    <a href="../pages/LogIn.php">LogIn</a>
                <?php } ?>
            </div>
            <form id="footer-search-form" class="footer-search" action="/search.php" method="get">
                <input type="text" class="searchbar" id="footer-searchbar" name="query" placeholder="Search for products">
                <input type="submit" value="Search">
            </form>
        </div>
    </footer>
<?php } ?>

<script>

    function loadMore(){
        var grid = document.getElementById('productsGrid');
        var products = document.querySelectorAll('#productsGrid li');
        var counter = 8;
        for (var product of products) {
            if (product.style.display === 'none'){
                product.style.display = 'block';
                counter--;
                if (counter === 0) {
                    break;
                }
            }
        } 

        var lastProduct = products[products.length - 1];
        if (lastProduct.style.display === 'block'){
            var loadMoreButton = document.getElementById('loadMore');
            loadMoreButton.style.display = 'none';
        }
    }


if (window.location.pathname === "/pages") {


    window.addEventListener('scroll', function() {
        var element = document.getElementById('fixedHeader');
        var element2 = document.getElementById('searchInput');
        var element3 = document.getElementById('searchImg');
        var anuncio = document.getElementById('announce');
        var nome = document.getElementById('nomeheader');

        
        if (window.scrollY > 0) {
            element.classList.add('scrolled');
            anuncio.classList.add('scrolled');
            nome.classList.remove('name');
        }
        else {
            element.classList.remove('scrolled');
            anuncio.classList.remove('scrolled');
            nome.classList.add('name');


            element3.style.display = 'none';
        }

        if (window.scrollY > 435){
            element3.style.display = 'block';
            element2.style.display = 'block';
            element2.style.opacity = '1';
            
        }
        else if (window.scrollY < 435){
            element2.style.display = 'none';
            element3.style.display = 'none';
        }
    });
}


window.onload = function(){

var inputHero = document.querySelector('#hero-input');
inputHero.addEventListener('keydown', function(event){
    if (event.key === 'Enter') {
        event.preventDefault();
        var searchValue = inputHero.value;
        window.location.href = `../pages/search.php?search=${searchValue}`;
    }
});

var inputFooter = document.querySelector('#footer-searchbar');
inputFooter.addEventListener('keydown', function(event){
    if (event.key === 'Enter') {
        event.preventDefault();
        var searchValue = inputFooter.value;
        window.location.href = `../pages/search.php?search=${searchValue}`;
    }
});

var formFooter = document.querySelector('#footer-search-form');
formFooter.addEventListener('submit', function(event){
    event.preventDefault();
    var searchValue = document.querySelector('#footer-searchbar').value;
    window.location.href = `../pages/search.php?search=${searchValue}`;
});

var searchImgContainer = document.querySelector('.searchImgContainer');
searchImgContainer.addEventListener('click', function(event){
    event.preventDefault();
    var searchValue = document.querySelector('#searchInput').value;
    window.location.href = `../pages/search.php?search=${searchValue}`;
});


var element = document.getElementById('searchImg');
var element2 = document.getElementById('searchInput');
var counter = 1;

        element2.addEventListener('keydown', function(event){
            if (event.key === 'Enter') {
            var searchValue = element2.value;
            window.location.href = `../pages/search.php?search=${searchValue}`;
            }
        });

element.addEventListener('click', function(){
    console.log('click event fired');
    counter++;
    console.log('counter:', counter);
    if (counter % 2 !== 1) {
        element2.classList.add('active');
        console.log('active class added');

    } else {
        element2.classList.remove('active');
        console.log('active class removed');
    }
});

}





/*window.addEventListener('load', function() {
    var searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        var searchValue = searchInput.value;
        var products = document.querySelectorAll('#productsGrid li');
        products.forEach(function(product) {
            var productName = product.querySelector('h3').innerText;
            if (productName.toLowerCase().includes(searchValue.toLowerCase())) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    });
});*/

</script>
