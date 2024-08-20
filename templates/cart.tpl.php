<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>

<!DOCTYPE html>
<html lang="en-US">
<link href="../css/productPage.css" rel="stylesheet">
<link href="../css/landingPage.css" rel="stylesheet">
<link href="../css/cart.css" rel="stylesheet">
<script src="../javascript/searchbar.js" defer></script>
<script src="../javascript/cart.js" defer></script>




<head>
    <title>ProductPage</title>
    <meta charset="UTF-8">
</head>
<body>
<?php function drawCart($session, $products, $db){?>

<section id="cartSection" class="cartContainer">
    <div class="inlineContain">
    <div class="topFlex" id="first">
        <div>Your Cart</div>
    </div>

    <div class = "overflowContainer">
    <?php 
    $isFirstProduct = true;
    foreach($products as $product){ 
        if($isFirstProduct) {
    ?>
        <div class="topFlex" id="second">
            <div class="productHeader">PRODUCT INFORMATION</div>
            <div class="priceHeader" id="price">PRICE</div>
            <div id="seller">SELLER</div>
        </div>
    <?php 
        $isFirstProduct = false;
        }
    ?>
        <li class="topFlex" id="productlisting"> 
            <div class="productInfo">
                <div id="imgproduct">
                    <img src="../<?= $product->imageUrl ?>" alt="<?= $product->name ?>">
                </div>
                <div class="infoList">
                    <h3><a id="productHref" href="product.php?id=<?= $product->id?>" title="<?=$product->name?>"><?= $product->name ?></a></h3>
                    <div><?= $product->category ?></div>
                    <div id="removeDiv"><button
                    id="remover" 
                    data-price='<?=$product->price?>'
                    data-productId='<?=$product->id?>'
                    data-buyerId='<?=user::getUserById($db,$session->getId())->username?>' 
                    class="removeButton">Remove from Cart</button></div>
                </div>
            </div>
                <div class="priceInfo">
                    <?= $product->price ?>
                </div>
                <div class="sellerInfo">
                <div id="productSeller"><a id="productSeller" href="profile.php?id=<?=$product->SellerID?>"><?= (user::getUserById($db, $product->SellerID))->firstName . ' ' . (user::getUserById($db, $product->SellerID))->lastName ?></a></div>
            </div>
        </li>
    <?php } ?>
    </div>
    <button class="goBack" onclick="window.location.href='search.php'"> Browse Products</button>    </div>

    <div class="inlineContain" id="rightSide">
        <div class="topFlex" id="first">
            <div>Summary</div>
            <div><?php 
            $total = 0;
            $count = 0;
            foreach($products as $product){
                $total += $product->price;
                $count++;
            }
            ?></div>
        </div>
        <div class="topFlex" id="second">
    <div>Products</div>
    <div></div>
    <div id="totalProducts"><?php echo $count; ?></div>
        </div>
        <div class="topFlex" id="second">
            <div>Price</div>
            <div></div>
            <div id="totalPrice">$<?php echo $total; ?></div>
        </div>
        <div id="second" class="topFlex">
            <div>Shipping</div>
            <div></div>
            <div class="shippingPrice" id="shipping">$0.00</div>
        </div>
        <div class="topFlex" id="finalPrice">
            <div>FINAL PRICE</div>
            <div></div>
            <div id="finalPriceValue">$<?php echo $total; ?></div>
        </div>
        <div id="buttonDiv">
            <button 
            data-productsLength=<?=$count?>
            id="checkoutButton">Checkout</button>
        </div>
    </div>

</section>


<section class="cartContainer" id="paymentSection">
    <div class="inlineContain">
    <div class="topFlex" id="first">
        <div>Payment & Shipping</div>
    </div>

    <div class = "overflowContainer">
    <li class="topFlex" id="productlisting"> 

        <div class="paymentMethods">
            <h3>Choose a Payment Method</h3>            
            <div class="radioinputs">
                <input type="radio" id="mbWay" name="paymentMethod" value="mbWay" onclick="showForm('mbWayForm')">
                <label for="mbWay"><img src="../assets/mbway.png" alt="MB WAY" id="mbwayimg" 
                onclick="showForm('mbWayForm')">
                MB WAY</label><br>
            </div>
            <form id="mbWayForm" style="display: none;">
                <label for="phoneNumber">Phone Number:</label>
                <input type="text" id="phoneNumber" name="phoneNumber" value="+351" required maxlength="13">
                <p style="margin-top:0em" id="atmtext">Submit your payment through the MBWAY Mobile App in the next 24 hours.</p>
            </form>


            <div class="radioinputs">
                <input type="radio" id="creditCard" name="paymentMethod"
                onclick="showForm('creditCardForm')"
                value="creditCard">
                <label for="creditCard">
                    Credit Card <img src="../assets/visa.webp" alt="VISA" id="visaimg">
                    <img src="../assets/mastercard.svg" alt="mc" id="mcimg"></label>
            </div>
            <form id="creditCardForm" style="display: none;">
                <label for="cardNumber">Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber" required><br>
                
                <label for="expiryDate">Expiry Date:</label>
                <div id="expiryDate">
                <select id="expiryMonth" name="expiryMonth">
                    <option value="">Month</option>
                    <?php for($i=1; $i<=12; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
                <select id="expiryYear" name="expiryYear">
                    <option value="">Year</option>
                    <?php for($i=date("Y"); $i<=2032; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select></div><br>
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required><br>
            </form>


            <div class="radioinputs">
                <input type="radio" id="ATM" name="paymentMethod" value="ATM"
                onclick="showForm('ATMForm')">
                <label for="ATM"><img src="../assets/multibanco.png" alt="MB" id="mbimg">
                    ATM</label><br>
            </div>
            <form id="ATMForm" style="display: none;">
                    <p id="atmtext">Proceed to an ATM within the next 24 hours to complete your payment.</p>
                </form>
        </div>
    </li>
    <li class="topFlex" id="shippingOptions">
    <div class="shippingMethods">

            <h3>Choose a Shipping Method</h3>
        <div class="radioinputs">
            <input type="radio" id="expressDelivery" name="shippingMethod" value="expressDelivery">
            <label for="expressDelivery">Express Delivery - Additional $10 per product</label><br>
        </div>
        
        <div class="radioinputs">
            <input type="radio" id="standardShipping" name="shippingMethod" value="standardShipping">
            <label for="standardShipping">Standard Shipping - Additional $5 per product</label><br>
        </div>
        
        <div class="radioinputs">
            <input type="radio" id="economyShipping" name="shippingMethod" value="economyShipping">
            <label for="economyShipping">Economy Shipping - Additional $2 per product</label><br>
        </div>

    </div>
</li>

    </div>
    <button id="backToCart" class="goBack"> Go Back to the Cart</button>


    </div>

    <div class="inlineContain" id="rightSide">
        <div class="topFlex" id="first">
            <div>Summary</div>
            <div></div>
        </div>
        <div class="topFlex" id="second">
    <div>Products</div>
    <div></div>
    <div id="totalProducts" class="count2"><?php echo $count; ?></div>
        </div>
        <div class="topFlex" id="second">
            <div>Price</div>
            <div></div>
            <div id="totalPrice" class="totalPrice">$<?php echo $total; ?></div>
        </div>
        <div id="second" class="topFlex">
            <div>Shipping</div>
            <div></div>
            <div class="shippingPrice" id="shipping">$0.00</div>
        </div>
        <div class="topFlex" id="finalPrice">
            <div>FINAL PRICE</div>
            <div></div>
            <div id="finalPriceValue" class="finalPriceValue">$<?php echo $total; ?></div>
        </div>
        <div id="buttonDiv">
            <button
            data-buyerId=<?=user::getUserById($db,$session->getId())->username?> 
            id="payButton">PAY</button>
        </div>
    </div>

</section>
<?php } ?>



<script>




document.addEventListener('DOMContentLoaded', function() {

    var button = document.getElementById('payButton');

    function firstClickListener() {
        console.log('First click listener triggered');
        this.textContent = "Confirm Payment";
        this.removeEventListener('click', firstClickListener);
        var userId = this.getAttribute('data-buyerId');
        //relembrar que o userId a este ponto é o username devido a certas mudanças inesperadas
        console.log('User ID:', userId);
        this.addEventListener('click', secondClickListener.bind(null, userId));
    }

    function secondClickListener(userId, event) {
        console.log('Second click listener triggered');
        var confirmation = confirm("Confirm Payment");
        if (confirmation) {
            var paymentMethodElement = document.querySelector('input[name=paymentMethod]:checked');
            var phoneNumberElement = document.querySelector("#phoneNumber");
            var cardNumberElement = document.querySelector("#cardNumber");
            var expiryMonthElement = document.querySelector("#expiryMonth");
            var expiryYearElement = document.querySelector("#expiryYear");
            var cvvElement = document.querySelector("#cvv");
            var shippingMethodElement = document.querySelector('input[name=shippingMethod]:checked');

            if (!shippingMethodElement) {
            alert("Please select a shipping method.");
            return;
            }
            else{
                var shippingPrice = 0;
                if(shippingMethodElement.value === "expressDelivery"){
                    shippingPrice = 10;
                }
                else if(shippingMethodElement.value === "standardShipping"){
                    shippingPrice = 5;
                }
                else if(shippingMethodElement.value === "economyShipping"){
                    shippingPrice = 2;
                }
                var totalPrice = parseFloat(document.getElementById('totalPrice').textContent.replace('$',''));
                var totalProducts = parseInt(document.getElementById('totalProducts').textContent);
                var finalPrice = totalPrice + (shippingPrice * totalProducts);
                document.getElementById('finalPriceValue').textContent = "$" + finalPrice;
            }


            if (!paymentMethodElement) {
                alert("Please select a payment method.");
                return;
            }

            if (paymentMethodElement.value === "mbWay" && !phoneNumberElement.value.size() > 4) {
                alert("Please enter a valid phone number.");
                return;
            }

            if (paymentMethodElement.value === "creditCard" && (!cardNumberElement.value || !expiryMonthElement.value || !expiryYearElement.value || !cvvElement.value)) {
                alert("Please enter all credit card details.");
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../actions/action_payCart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    console.log('AJAX response:', this.responseText);
                    if(this.responseText == "Success"){
                        alert("Payment Successful!");
                        //devido a esta linha tivemos de alterar para o userId na verdade ser o username
                        window.location.href = "../pages/profileSettingsOrderHistory.php?username=" + userId;
                    } else {
                        alert("Payment Failed!");
                    }
                }
            }
            xhr.onerror = function() {
                console.error('AJAX error:', this.status, this.statusText);
            }
            xhr.send(`userId=${userId}&shippingPrice=${shippingPrice}`);
        }
    }

    if (button) {
        button.addEventListener('click', firstClickListener);
    } else {
        console.error('Button could not be found');
    }






});



</script>