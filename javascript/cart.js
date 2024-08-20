function showForm(formId) {
    document.getElementById('mbWayForm').style.display = 'none';
    document.getElementById('creditCardForm').style.display = 'none';
    document.getElementById('ATMForm').style.display = 'none';
    
    document.getElementById(formId).style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('checkoutButton').addEventListener('click', function() {
        if (document.getElementById('checkoutButton').getAttribute('data-productsLength') == 0) {
            document.getElementById('checkoutButton').textContent = 'Add products to the Cart first!';
        }
        else{
            document.getElementById('paymentSection').style.display = 'block';
            document.getElementById('paymentSection').scrollIntoView({behavior: "smooth"});
        }
    });
    
    document.getElementById('backToCart').addEventListener('click', function() {
        document.getElementById('cartSection').scrollIntoView({behavior: "smooth"});
        document.getElementById('paymentSection').style.display = 'none';
    });
    
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
    

    
    removeFromCart = function(productID, buyerID, priceInfoElement){
        button = document.querySelector('.removeButton');

    
        var productPrice = priceInfoElement;

    
        var removeButton = document.querySelector('.removing');
        removeButton.classList.remove('removing');
        removeButton.parentNode.parentNode.parentNode.parentNode.remove();
    
        var totalPriceElement = document.getElementById('totalPrice');
        var totalPriceElement2 = document.querySelector('.totalPrice');
    
        var finalPriceElement = document.getElementById('finalPriceValue');
        var finalPriceElement2 = document.querySelector('.finalPriceValue');
    
        var totalProductsElement = document.getElementById('totalProducts');
        var totalProductsElement2 = document.querySelector('.count2');
    
        var totalPrice = parseFloat(totalPriceElement.textContent.replace('$', ''));
        var totalProducts = parseInt(totalProductsElement.textContent);
    
        totalPrice -= productPrice;
        totalProducts -= 1;
    
        totalPriceElement.textContent = '$' + totalPrice.toFixed(2);
        totalPriceElement2.textContent = '$' + totalPrice.toFixed(2);
    
        finalPriceElement.textContent = '$' + totalPrice.toFixed(2);
        finalPriceElement2.textContent = '$' + totalPrice.toFixed(2);
    
        totalProductsElement.textContent = totalProducts;
        totalProductsElement2.textContent = totalProducts;
    
        fetch("../actions/action_deleteOrder.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                productID: productID,
                buyerID: buyerID,
            })
        })
        .then(response => response.text())
        .then(data => {
            //console.log("Request succeeded. Response: ", data);
        })
        .catch(error => {
            //console.log("Request failed. ", error);
        });
    }
    
    var removeButtons = document.querySelectorAll('.removeButton');
    removeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var productID = this.getAttribute('data-productId');
            var buyerID = this.getAttribute('data-buyerId');

            var priceInfoElement = this.getAttribute('data-price');
            button.classList.add('removing');
            removeFromCart(productID, buyerID,priceInfoElement);
        });
    });



    
var originalTotalPrice = parseFloat(document.querySelector('.finalPriceValue').innerHTML.substring(1));
var totalProducts = document.getElementById('totalProducts').textContent;
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



        var shippingPrices = document.querySelectorAll('.shippingPrice');
        shippingPrices.forEach(function(element) {
            element.innerHTML = '$' + (shippingCost * totalProducts).toFixed(2);
        });


        var totalPrices = document.querySelectorAll('.finalPriceValue');
        totalPrices.forEach(function(element) {
            let total = originalTotalPrice;
            let finalPrice = total + shippingCost * totalProducts;
            element.innerHTML = '$' + finalPrice.toFixed(2);
        });

    });
});


});