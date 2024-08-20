addToCart = function(productID, buyerID, sellerID, isAvailable, clickedButton){

    console.log('addToCart function called with productID: ' + productID + ', buyerID: ' + buyerID + ', sellerID: ' + sellerID + ', isAvailable: ' + isAvailable);
    var cartButton = clickedButton;
    cartButton.innerHTML = "Loading...";
    cartButton.classList.add('loading');
    cartButton.disabled = true;

    console.log("addToCart function called with productID: " + productID + ", buyerID: " + buyerID + ", sellerID: " + sellerID + ", isAvailable: " + isAvailable);

    if (isAvailable == 1) {
        console.log("Product is available, sending POST request to action_createOrder.php");
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
            console.log("POST request completed with data: " + data);
            cartButton.innerHTML = "Successfully added to cart";
            cartButton.classList.remove('loading');
            cartButton.classList.add('added');
        });
    } else {
        console.log("Product is not available");
        cartButton.innerHTML = "Add to Cart";
        cartButton.classList.remove('loading');
        cartButton.disabled = false;
    }
}

var cartButtons = document.getElementsByClassName('cartButtonLp');
for (let i = 0; i < cartButtons.length; i++) {
    let cartButton = cartButtons[i];
    if (cartButton.textContent != "Already in your Cart") {
        cartButton.addEventListener('click', function() {
            var productID = this.getAttribute('data-productId');
            var buyerID = this.getAttribute('data-sessionId');
            var sellerID = this.getAttribute('data-SellerId');
            var isAvailable = this.getAttribute('data-isAvailable');
            addToCart(productID, buyerID, sellerID, isAvailable, this);
        });
    }
}