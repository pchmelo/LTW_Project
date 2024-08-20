<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>

<!DOCTYPE html>
<html lang="en-US">
<link href="../css/productPage.css" rel="stylesheet">
<link href="../css/landingPage.css" rel="stylesheet">
<script src="../javascript/searchbar.js" defer></script>
<script src="../javascript/addToCart.js" defer></script>



<head>
    <title>ProductPage</title>
    <meta charset="UTF-8">
</head>
<body>
<?php 
function drawProductInfo($session, $cartproducts, $product, $nextProductId, $prevProductId, $db){
    $seller = user::getUserById($db, $product->SellerID);
?>
    <div id="containerProduct">
        <?php if($prevProductId != null) { ?>
            <button class="nextProduct" id="back" onclick="window.location.href='../pages/product.php?id=<?= $prevProductId ?>'"></button>
        <?php } ?>
        <section id="s1">
            <div>
                <img src="../<?=$product->imageUrl?>" id="imagemproduto" alt="<?= $product->name ?>">
            </div>
            <div id="information">
                <div id="info">
                    <h3 id="name"><?= $product->name ?></h3>
                    <h3 id="price">$<?= $product->price ?></h3>                    
                    <p id="category">Category: <?= $product->category ?></p>
                    <p id="brand">Brand: <?= $product->brand ?></p>
                    <p id="model">Model: <?= $product->model ?></p>
                    <p id="size">Size: <?= $product->tamanho ?></p>
                    <p id="condition">Condition: <?= $product->condition ?></p>
                    <p id="seller">Seller: <?= $seller->firstName ?> <?= $seller->lastName ?></p>
                    
                    <div id="buttons">
                        <?php if($session->getId() != $product->SellerID) { ?>
                            <button id="buy" onclick="buyProduct()">Buy</button>
                            <?php
                            if(!$session->isLoggedIn()) { ?>
                                <button id="toLogin" onclick="window.location.href='../pages/LogIn.php';">
                                    Log In to Add to Cart
                                </button>
                            <?php } else { ?>
                                <button id="cart" 
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
                            <div id ="sharedm">
                                <button id="DM">Send Message</button>
                                <button id="share">Share</button>
                            </div>
                        <?php } else { ?>
                            <button id="Edit" 
                            onclick="window.location.href='../pages/productSubmitEdit.php?username=<?php echo user::getUserById($db,$session->getId())->username; ?>&prod_id=<?php echo $product->id; ?>'">
                            Edit Product</button>

                            <button id="Unlist"
                            data-productId='<?=$product->id?>' 
                            >Unlist Product</button>
                            <div id ="sharedm">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
        <?php if($nextProductId != null) { ?>
            <button class="nextProduct" id="next" onclick="window.location.href='../pages/product.php?id=<?= $nextProductId ?>'"></button>
        <?php } ?>
    </div>
    <h1>Product description</h1>
    <p style="margin-bottom: 5em" id="desc">
    <?= $product->description ?></p>

<?php } ?>
 


<?php function drawComments($session, $product, $comments, $db){?>

<section id="comments">
    <h1 style="text-align: center">Questions & Answers</h1>
    <?php if($session->isLoggedIn()) { ?>
        <form onsubmit="event.preventDefault(); publishComment();" class="commentForm" method="post">
            <input type="hidden" name="productID" value="<?= $product->id ?>">
            <input type="hidden" name="userID" value="<?= $session->getId() ?>">
            <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
            <textarea name="content" id="commentContent" placeholder="Ask a Question..."></textarea>
            <button id="commentSubmit" type="submit">Submit</button>
        </form>
    <?php } ?>

    <ul id="commented">
        <?php foreach($comments as $comment) {
            $user = user::getUserById($db, $comment->author);
            ?>
            <li>
                <img src="../assets/placeholder-1-1.webp">
                <div class="comment">
                    <div class="commentUser"><?= $user->firstName ?> <?= $user->lastName ?></div>
                    <div class="commentContent"><?= $comment->content ?></div>

                    <?php if ($session->isLoggedIn()) { ?>
                    <div><button class="responder" onclick="toggleReplyForm(this)">Reply</button></div> 
                    <?php } ?>

                    <div><button class="seeReplies" onclick="toggleReplies(this)"><span>&#x25BC;</span> Replies</button></div>
                    <div class="replies">
                        <form id="replyForm" class="commentForm" style="display: none;" onsubmit="event.preventDefault(); publishReply(this);" method="post">
                            <input type="hidden" name="commentID" value="<?= $comment->commentID ?>">
                            <input type="hidden" name="userID" value="<?= $session->getId() ?>">
                            <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
                            <textarea class="areaReply" name="content" placeholder="Reply to this Question..."></textarea>
                            <div class="alignButtonReply"><button id="replySubmit" type="submit">Submit</button></div>
                        </form>
                        <ul id="replysUl" style="display:none">
                        <?php foreach(Reply::getRepliesFromComment($db, $comment->commentID) as $reply) {
                            $replyUser = user::getUserById($db, $reply->authorID);
                            ?>
                            <li class="replylist">
                            <img src="../assets/placeholder-1-1.webp">
                            <div class="comment">
                                <div class="commentUser"><?= $replyUser->firstName ?> <?= $replyUser->lastName ?></div>
                                <div class="commentContent"><?= $reply->replyText ?></div>
                            </div>
                            </li>
                        <?php } ?>
                        </ul>
                        
                    </div>
                </div>
            </li>
        <?php } ?>
    </ul>
</section>
<?php } ?>



    <?php function drawRelated($session, $categoryproducts, $product, $db){?>
      <section id="productsGrid">
            <h2>Related Products</h2>

            <ul>
      <?php foreach($categoryproducts as $relatedProduct) {
        if ($relatedProduct->id != $product->id && $session->getId() != $relatedProduct->SellerID && $relatedProduct->isAvailable == 1){ ?>
        <li> 
        <div id="productImage">
            <img src="../<?= $relatedProduct->imageUrl ?>" alt="<?= $relatedProduct->name ?>"></div>
              <div>
              <h3><a id="productPage" href="../pages/product.php?id=<?= $relatedProduct->id?>" title="<?=$relatedProduct->name?>"><?= $relatedProduct->name ?></a></h3>
              <p><?= $relatedProduct->description ?></p>
              <p>$<?= $relatedProduct->price ?></p>
              <p id="productSeller">@<a id="productSeller" href="../pages/profile.php?username=<?=user::getUserById($db, $relatedProduct->SellerID)->username?>"><?= (user::getUserById($db, $relatedProduct->SellerID))->username ?></a></p>
                </div>
                </li>
      <?php }
    } ?>

    </section>
<?php } ?>



<script>

function toggleReplies(element) {
    var replies = element.parentNode.parentNode.querySelector('.replies').querySelector('#replysUl')
    if(replies.style.display === "none") {
        replies.style.display = "block";
        replies.style.maxHeight = "20em";
        replies.style.overflow = "auto";
        element.classList.add("active");
    } else {
        replies.style.display = "none";
        element.classList.remove("active");
    }
}

function toggleReplyForm(element) {

    var form = element.parentNode.parentNode.querySelector('#replyForm');
    if(form.style.display === "none") {
        form.style.display = "block";
    } else {
        form.style.display = "none";
    }
}



function publishComment() {
    var commentContent = document.getElementById('commentContent').value;
    var productID = '<?= $product->id ?>';
    var userID = '<?= $session->getId() ?>';
    var username = "<?= user::getUserById($db, $session->getId())->username ?>";


    fetch('../actions/action_publishComment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            productID: productID,
            userID: userID,
            content: commentContent
        })
    })
    .then(response => response.text())
    .then(data => {

        var newCommentLi = document.createElement('li');
        newCommentLi.innerHTML = `
            <img src="../assets/placeholder-1-1.webp">
            <div class="comment">
                <div class="commentUser"><?php echo user::getUserById($db, $session->getId())->firstName ?> <?php echo user::getUserById($db, $session->getId())->lastName ?></div>
                <div class="commentContent">${commentContent}
                <div><button class="responder">Reply</button></div>
                <div class="replies">
                <form id="replyForm" onsubmit="event.preventDefault(); publishReply(this);" class="commentForm" style="display: none;" method="post">
                    <input type="hidden" name="commentID" value="${data}">
                    <input type="hidden" name="userID" value="${userID}">
                    <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
                    <textarea name="content" placeholder="Write your reply here..."></textarea>
                    <button id="replySubmit" type="submit">Submit</button>
                </form><ul id="replysUl"></ul>
                </div>
            </div>
        `;

        var commentsSection = document.getElementById('commented');
        commentsSection.prepend(newCommentLi);
        document.getElementById('commentContent').value = '';

        newCommentLi.querySelector('.responder').addEventListener('click', function() {
            toggleReplyForm(this);
        });
    });
}


function publishReply(element) {  
    
    var form = element;
    var userID = form.querySelector('input[name="userID"]').value;
    var content = form.querySelector('textarea[name="content"]').value;
    var commentID = form.querySelector('input[name="commentID"]').value;

fetch('../actions/action_publishReply.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({
        userID: userID,
        content: content,
        commentID: commentID
    })
})

.then(response => response.text())
.then(data => {

    var newReplyLi = document.createElement('li');
    newReplyLi.classList.add("replylist");
    newReplyLi.innerHTML = `
        <img src="../assets/placeholder-1-1.webp">
        <div class="comment">
            <div class="commentUser"><?php echo user::getUserById($db, $session->getId())->firstName ?> <?php echo user::getUserById($db, $session->getId())->lastName ?></div>
            <div class="commentContent">${content}</div>
        </div>
    `;
    element.style.display = 'none';
    var replies = element.parentNode.querySelector('#replysUl');

    replies.appendChild(newReplyLi);
});
}

function buyProduct() {
    <?php if($session->isLoggedIn()): ?>
        window.location.href = `../pages/cartSingleProduct.php?prod_id=<?=$product->id?>&username=<?=user::getUserById($db, $session->getId())->username?>`;
    <?php else: ?>
        window.location.href = '../pages/login.php';
    <?php endif; ?>
}
</script>
    

<script>

document.addEventListener('DOMContentLoaded', function() {
    const nextButton = document.getElementById('next');
    const backButton = document.getElementById('back');

    nextButton.addEventListener('click', function() {
        const nextProductId = this.getAttribute('data-product-id');
        if (!nextProductId) return;
        window.location.href = `../pages/product.php?id=${nextProductId}`;
    });

    backButton.addEventListener('click', function() {
        const prevProductId = this.getAttribute('data-product-id');
        if (!prevProductId) return;
        window.location.href = `../pages/product.php?id=${prevProductId}`;
    });
});

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

    Unlist = function(productID){
        var unlistButton = document.getElementById('Unlist');
        unlistButton.innerHTML = "Click again to confirm";
        unlistButton.classList.add('confirm');

        unlistButton.onclick = function() {
            unlistButton.innerHTML = "Loading...";
            unlistButton.classList.remove('confirm');
            unlistButton.classList.add('loading');
            unlistButton.disabled = true;


            fetch("../actions/action_unlistProduct.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    productID: productID,
                })
            })
            .then(response => response.text())
            .then(data => {
                unlistButton.innerHTML = "Product Removed Successfully";
                unlistButton.classList.remove('loading');
                unlistButton.classList.add('added');
            });
        }
    }

    addToCart = function(productID, buyerID, sellerID, isAvailable){

        var cartButton = document.getElementById('cart');
        cartButton.innerHTML = "Loading...";
        cartButton.classList.add('loading');
        cartButton.disabled = true;


        if (isAvailable == 1) {
            fetch("../actions/action_createOrder.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    productID: productID,
                    buyerID: buyerID,
                    sellerID: sellerID
                })
            })
            .then(response => response.text())
            .then(data => {
                cartButton.innerHTML = "Successfully added to cart";
                cartButton.classList.remove('loading');
                cartButton.classList.add('added');
            });
        } else {
            cartButton.innerHTML = "Add to Cart";
            cartButton.classList.remove('loading');
            cartButton.disabled = false;
        }
    }

    var cartButton = document.getElementById('cart');
    if (cartButton) {
        if (cartButton.textContent != "Already in your Cart") {
            cartButton.addEventListener('click', function() {
                var productID = this.getAttribute('data-productId');
                var buyerID = this.getAttribute('data-sessionId');
                var sellerID = this.getAttribute('data-SellerId');
                var isAvailable = this.getAttribute('data-isAvailable');
                addToCart(productID, buyerID, sellerID, isAvailable);
            });
        }
    } else {
        console.log('Cart button not found');
    }

    var unlistButton = document.getElementById('Unlist');

    if(unlistButton) {
        unlistButton.addEventListener('click', function() {
            var productID = this.getAttribute('data-productId');
            Unlist(productID);
        });
    } else {
        console.log('Unlist button not found');
    }

});
</script>