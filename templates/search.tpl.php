<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>


<!DOCTYPE html>
<html lang="en-US">
<link href="../css/profilePage.css" rel="stylesheet">
<link href="../css/landingPage.css" rel="stylesheet">
<link href="../css/searchPage.css" rel="stylesheet">
<script src="../javascript/search.js" defer></script>
<script src="../javascript/searchbar.js" defer></script>
<script src="../javascript/addToCart.js"></script>




<head>
    <title>Search</title>
    <meta charset="UTF-8">
</head>

<?php function productsGridSearch($session, $cartproducts, array $products,PDO $db) { ?>
    <main>
        <aside id="filters">
          <h1>Filters</h1>
            <div class="dropdown">
                <button class="dropdown-button" id="category"><div>Category</div><div class="droparrow"></div></button>
                <div class="dropdown-content">
                </div>
            </div>
            <div class="dropdown">
                <button class="dropdown-button"><div>Maximum Price</div><div class="droparrow"></div></button>
                <div class="dropdown-content">
                </div>
            </div>
            <div class="dropdown">
                <button class="dropdown-button"><div>Brand</div><div class="droparrow"></div></button>
                <div class="dropdown-content">
                </div>
            </div>
            <div class="dropdown">
                <button class="dropdown-button"><div>Model</div><div class="droparrow"></div></button>
                <div class="dropdown-content">
                </div>
            </div>
            <div class="dropdown">
                <button class="dropdown-button"><div>Size</div><div class="droparrow"></div></button>
                <div class="dropdown-content">
                </div>
            </div>
            <div class="dropdown">
                <button class="dropdown-button"><div>Condition</div><div class="droparrow"></div></button>
                <div class="dropdown-content">
                </div>

        </aside>
        <section id="productsGrid" class="gridsection">
            <h4><span class="divtitle">Search Results</span></h4>
            <div id="divfiltros" class="filtros">No Filters</div>
            <ul>
            <?php foreach($products as $product){ 
                if($_SESSION['id'] == $product->SellerID) continue;
                if($product->isAvailable == 0) continue;
            ?>
                <li id="productli" class="escolhido"> 
                    <div id="productImage">
                        <img src="../<?= $product->imageUrl ?>" alt="<?= $product->name ?>">
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
                    <div id="productSeller">@<a id="productSeller" href="../pages/profile.php?username=<?=user::getUserById($db,$product->SellerID)->username?>"><?= (user::getUserById($db, $product->SellerID))->username ?></a></div>
                    <div>
                        <div class="h3"><a id="productPage" href="../pages/product.php?id=<?= $product->id?>" title="<?=$product->name?>"><?= $product->name ?></a></div>
                        <p class="description"><?= $product->description ?></p>
                        <p>Condition: <?= $product->condition ?></p>
                        <p class="priceInGrid">$<?= $product->price ?></p>
                    </div>
                </li>
            <?php } ?>
            </ul>
        </section>
    </main>
</body>
<?php } ?>


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



function activateFilters(){
  document.addEventListener('DOMContentLoaded', function() {


    var dropdownButtons = document.querySelectorAll('.dropdown-button');

    var preChosenCategory = <?php echo json_encode($_GET['category'] ?? null); ?>;
    

    var categoriesArray = <?php echo json_encode($categories); ?>;
    var brandsArray = <?php echo json_encode($brands); ?>;
    var modelsArray = <?php echo json_encode($models); ?>;
    var sizesArray = <?php echo json_encode($sizes); ?>;
    var conditionsArray = <?php echo json_encode($conditions); ?>;

    var productsArray = <?php echo json_encode($products); ?>;
    var productlis = document.querySelectorAll('#productli');

    let search = document.querySelector('#searchInput');
    let clone = search.cloneNode(true);
    search.parentNode.replaceChild(clone, search);

    search = clone;
    let searchvalue = '';

    search.value = <?php echo json_encode($searchValue) ?>;

    var first = true;


    search.addEventListener('input', function() {
    searchvalue = search.value;

    //verificar se a search tem pelomenos 3 digitos
    if (searchvalue.length > 2 || (first)) {
        first = false;
        //encontrar o produto associado
        productlis.forEach(function(productli) {
            var productLink = productli.querySelector('#productPage');
            var productId = productLink.getAttribute('href').split('=')[1];
            var product = productsArray.find(function(product) {
                return product.id == productId;
            });

            productName = product.name.toLowerCase();
            searchvalue = searchvalue.toLowerCase();
            //filtrar produtos que nao correspondem à pesquisa
            if (productName.includes(searchvalue)) {
                if (productli.classList.contains('searchfilter')){
                    productli.classList.remove('searchfilter');
                }
            } else {
                productli.classList.add('searchfilter');
            }
        });
    }
    //nao filtrar caso nao haja pesquisa com pelomenos 3 digitos
    else{
        productlis.forEach(function(productli) {
            if (productli.classList.contains('searchfilter')){
                productli.classList.remove('searchfilter');
            }
        });
    }
});
search.dispatchEvent(new Event('input'));

search.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        searchvalue = search.value;
        
        productlis.forEach(function(productli) {
            var productLink = productli.querySelector('#productPage');
            var productId = productLink.getAttribute('href').split('=')[1];
            var product = productsArray.find(function(product) {
                return product.id == productId;
            });

            productName = product.name.toLowerCase();
            searchvalue = searchvalue.toLowerCase();
            //filtrar produtos que nao correspondem à pesquisa
            if (productName.includes(searchvalue)) {
                if (productli.classList.contains('searchfilter')){
                    productli.classList.remove('searchfilter');
                }
            } else {
                productli.classList.add('searchfilter');
            }
        });
    }
});


    checkboxmap = {};

    if (preChosenCategory) {
        checkboxmap['Category-' + preChosenCategory] = 'active';
    }

    dropdownButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var dropdown = button.nextElementSibling;
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
                button.classList.remove('ativo');

                dropdown.addEventListener('transitionend', function() {
                    while (dropdown.firstChild) {
                        dropdown.removeChild(dropdown.firstChild);
                        var MarginTop = parseFloat(footer.style.marginTop);
                    }
                }, { once: true });
            } else {
                dropdown.classList.add('show');
                button.classList.add('ativo');

                var items;
                switch(button.textContent) {
                    case 'Category':
                        items = <?php echo json_encode($categories); ?>;
                        break;
                    case 'Brand':
                        items = <?php echo json_encode($brands); ?>;
                        break;
                    case 'Model':
                        items = <?php echo json_encode($models); ?>;
                        break;
                    case 'Size':
                        items = <?php echo json_encode($sizes); ?>;
                        break;
                    case 'Condition':
                        items = <?php echo json_encode($conditions); ?>;
                        break;
                    case 'Maximum Price':
                        items = ['100', '250', '500', '750', '1000', '1500', '2000', '3000', '5000', '10000'];
                        break;
                    default:
                        items = [];
                }

                items.forEach(function(item) {

    var newItem = document.createElement('div');
    newItem.style.display = 'flex';
    newItem.style.justifyContent = 'left';
    newItem.style.alignItems = 'center';
    newItem.style.padding = '1em';


    var text = document.createElement('span');
    text.textContent = item;

    var checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.id = button.textContent;

    if (checkboxmap[checkbox.id + '-' + item] == 'active') {
    checkbox.checked = true;
    }
    else{
        checkbox.checked = false;
    }

    checkbox.style.marginRight = '0.5em';
    checkbox.style.cursor = 'pointer';
    checkbox.style.transform = 'scale(1.25)';

    checkbox.setAttribute('info', item);


    newItem.appendChild(checkbox);
    newItem.appendChild(text);
    
    dropdown.appendChild(newItem);
    dropdown.style.overflow = 'auto';
});

    var checkboxes = document.querySelectorAll('input[type="checkbox"]:not(#hamburger)');
    var counter = 0;
    var divfiltros = document.getElementById('divfiltros');
    

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {

            var relatedInfo = checkbox.getAttribute('info');
            var filterLabel = checkbox.id;
            var filterName = relatedInfo;
            console.log('filtername: ' + filterName);

            var maxPrice = 0;

            if (checkbox.checked) {
                checkboxmap[filterLabel + '-' + filterName] = 'active';
                if (filterLabel == 'Maximum Price') {
                    maxPrice = parseInt(filterName);
                }

            } else {
                checkboxmap[filterLabel + '-' + filterName] = 'inactive';
                if(filterLabel == 'Maximum Price') {
                    Object.keys(checkboxmap).forEach(function(key) {
                        if (key.includes('Maximum Price')) {
                            if (checkboxmap[key] == 'active') {
                                var priceValue = key.split('-')[1];
                                console.log(priceValue);
                                if(maxPrice < parseInt(priceValue)){
                                    maxPrice = parseInt(priceValue);
                                }
                            } 
                        }
                    });
                }
            }
            



        


            
            productlis.forEach(function(productli) {
                var productLink = productli.querySelector('#productPage');
                var productId = productLink.getAttribute('href').split('=')[1];
                var product = productsArray.find(function(product) {
                    return product.id == productId;
                });


                var categoryFilter = checkboxmap['Category' + '-' + product.category] || 'inactive';
                var brandFilter = checkboxmap['Brand' + '-' + product.brand] || 'inactive';
                var modelFilter = checkboxmap['Model' + '-' + product.model] || 'inactive';
                var sizeFilter = checkboxmap['Size' + '-' + product.tamanho] || 'inactive';
                var conditionFilter = checkboxmap['Condition' + '-' + product.condition] || 'inactive';

                var priceFilter = (product.price < maxPrice) ? 'active' : 'inactive';

                if (categoryFilter == 'active' || brandFilter == 'active' || modelFilter == 'active' || sizeFilter == 'active' || conditionFilter == 'active' || priceFilter == 'active') {
                    productli.style.display = 'block';
                    productli.classList.add('escolhido');


                    divfiltros.textContent = '';
                    var flag = true;
                    Object.keys(checkboxmap).forEach(function(key) {
                    if (checkboxmap[key] == 'active') {
                        flag = false;
                        var parts = key.split('-');
                        var filterLabel = parts[0];
                        var filterName = parts[1];
                        var newdiv = document.createElement('div');
                        newdiv.textContent = filterLabel + ': ' + filterName;
                        newdiv.classList.add('nomefiltro');
                        divfiltros.appendChild(newdiv);
                    }
                    });
                    if (flag) {
                        divfiltros.textContent = 'No Filters';
                    }
                    
                }
                else if(categoryFilter == 'inactive' && brandFilter == 'inactive' && modelFilter == 'inactive' && sizeFilter == 'inactive' && conditionFilter == 'inactive' || priceFilter == 'inactive') {
                    
    
                    productli.style.display = 'block';
                    productli.classList.add('escolhido');


                    divfiltros.textContent = '';
                    var flag = true;
                    Object.keys(checkboxmap).forEach(function(key) {

                    if (checkboxmap[key] == 'active') {
                        flag = false;
                        productli.style.display = 'none';
                        productli.classList.remove('escolhido');
                        var parts = key.split('-');
                        var filterLabel = parts[0];
                        var filterName = parts[1];
                        var newdiv = document.createElement('div');
                        newdiv.textContent = filterLabel + ': ' + filterName;
                        newdiv.classList.add('nomefiltro');
                        divfiltros.appendChild(newdiv);
                    }
                    });
                    if (flag) {
                        divfiltros.textContent = 'No Filters';
                    }
                }
                else {
                    productli.style.display = 'none';
                    productli.classList.remove('escolhido');

                }
            });
        const search = document.querySelector('#searchInput');
        search.dispatchEvent(new Event('input'));
        });
        checkbox.dispatchEvent(new Event('change'));
        const search = document.querySelector('#searchInput');
        search.dispatchEvent(new Event('input'));
        
    });



            }
        });
    });
});
}

activateFilters();



document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#category').dispatchEvent(new Event('click'));

});



</script>