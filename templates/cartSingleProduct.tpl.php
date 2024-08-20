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




<head>
    <title>Buying Page</title>
    <meta charset="UTF-8">
</head>
<body>


<?php function drawBuyProduct($session, $product, $db){?>
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

    </div>

    <div class="inlineContain" id="rightSide">
        <div class="topFlex" id="first">
            <div>Summary</div>
            <div></div>
        </div>
        <div class="topFlex" id="second">
            <div>Products</div>
            <div></div>
            <div id="totalProducts" class="count2"><?php echo 1; ?></div>
        </div>
        <div class="topFlex" id="second">
            <div>Price</div>
            <div></div>
            <div id="totalPrice" class="totalPrice">$<?php echo $product->price; ?></div>
        </div>
        <div id="second" class="topFlex">
            <div>Shipping</div>
            <div></div>
            <div class="shippingPrice" id="shipping">$0.00</div>
        </div>
        <div class="topFlex" id="finalPrice">
            <div>FINAL PRICE</div>
            <div></div>
            <div id="finalPriceValue" class="finalPriceValue">$<?php echo $product->price; ?></div>
        </div>
        <div id="buttonDiv">
            <button
            data-buyerId=<?=User::getUserById($db,$session->getId())->username?> 
            data-prodId=<?=$product->id?>
            id="payButton">PAY</button>
        </div>
    </div>

</section>
<?php } ?>


<script>

function showForm(formId) {
    document.getElementById('mbWayForm').style.display = 'none';
    document.getElementById('creditCardForm').style.display = 'none';
    document.getElementById('ATMForm').style.display = 'none';
    
    document.getElementById(formId).style.display = 'block';
}


document.addEventListener('DOMContentLoaded', function() {
    console.log('Document is ready');

    var button = document.getElementById('payButton');
    console.log('Button:', button);

    function firstClickListener() {
        console.log('First click listener triggered');
        this.textContent = "Confirm Payment";
        this.removeEventListener('click', firstClickListener);
        var userId = this.getAttribute('data-buyerId');
        var productId = this.getAttribute('data-prodId');
        //relembrar que o userId a este ponto é o username
        console.log('User ID:', userId);
        console.log('Product ID:', productId);
        this.addEventListener('click', secondClickListener.bind(null, userId, productId));
    }

    function secondClickListener(userId, productId, event) {
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
            xhr.open("POST", "../actions/action_paySingleProduct.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    console.log('AJAX response:', this.responseText);
                    if(this.responseText == "Success"){
                        alert("Payment Successful!");
                                    //relembrar que o userId a este ponto é o username
                        window.location.href = "../pages/profileSettingsOrderHistory.php?username=" + userId;
                    } else {
                        alert("Payment Failed!");
                    }
                }
            }
            xhr.onerror = function() {
                console.error('AJAX error:', this.status, this.statusText);
            }
            xhr.send(`userId=${userId}&productId=${productId}&shippingPrice=${shippingPrice}`);
        }
    }

    if (button) {
        button.addEventListener('click', firstClickListener);
    } else {
        console.error('Button could not be found');
    }



    var originalTotalPrice = parseFloat(document.querySelector('.finalPriceValue').innerHTML.substring(1));
console.log('Original total price:', originalTotalPrice);

var totalProducts = document.getElementById('totalProducts').textContent;
console.log('Total products:', totalProducts);

document.querySelectorAll('input[name="shippingMethod"]').forEach((radio) => {
    console.log('Adding event listener to radio button:', radio.id);
    radio.addEventListener('change', function() {
        let shippingCost;
        switch(this.value) {
            case 'expressDelivery':
                shippingCost = 10;
                break;
            case 'standardShipping':
                shippingCost = 5;
                break;
            case 'economyShipping':
                shippingCost = 2;
                break;
            default:
                shippingCost = 0;
        }

        console.log('Selected shipping method:', this.value, 'Shipping cost:', shippingCost);

        var shippingPrices = document.querySelectorAll('.shippingPrice');
        shippingPrices.forEach(function(element) {
            element.innerHTML = '$' + (shippingCost * totalProducts).toFixed(2);
        });

        console.log('Updated shipping prices');

        var totalPrices = document.querySelectorAll('.finalPriceValue');
        totalPrices.forEach(function(element) {
            let total = originalTotalPrice;
            let finalPrice = total + shippingCost * totalProducts;
            console.log('Total:', total, 'Final price:', finalPrice);
            element.innerHTML = '$' + finalPrice.toFixed(2);
        });

        console.log('Updated total prices');
    });
});

});

</script>