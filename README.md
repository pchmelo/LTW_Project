# Vintech

## Group ltw15g09

- Vasco Melo (up202207564) 50%
- Rafael Cunha (up202208957) 50%

## Install Instructions

    git clone https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw15g09.git
    git checkout final-delivery-v2
    cd database
    sqlite3 -init project.sql project.db
    cd ..
    php -S localhost:9000

## Screenshots

Homepage of our site
<img src="https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw15g09/blob/main/assets/homepage.png" alt="Image">

Search in our site
<img src="https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw15g09/blob/main/assets/search.png" alt="Image">

Product Display
<img src="https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw15g09/blob/main/assets/product.png" alt="Image">

## Implemented Features

**General**:

- [✅] Register a new account.
- [✅] Log in and out.
- [✅] Edit their profile, including their name, username, password, and email.

**Sellers** should be able to:

- [✅] List new items, providing details such as category, brand, model, size, and condition, along with images.
- [✅] Track and manage their listed items.
- [✅] Respond to inquiries from buyers regarding their items and add further information if needed.
- [✅] Print shipping forms for items that have been sold.

**Buyers** should be able to:

- [✅] Browse items using filters like category, price, and condition.
- [✅] Engage with sellers to ask questions or negotiate prices.
- [✅] Add items to a wishlist or shopping cart.
- [✅] Proceed to checkout with their shopping cart (simulate payment process).

**Admins** should be able to:

- [✅] Elevate a user to admin status.
- [✅] Introduce new item categories, sizes, conditions, and other pertinent entities.
- [✅] Oversee and ensure the smooth operation of the entire system.

**Security**:
We have been careful with the following security aspects:

- [✅] **SQL injection**
- [✅] **Cross-Site Scripting (XSS)**
- [✅] **Cross-Site Request Forgery (CSRF)**

**Password Storage Mechanism**: md5 / sha1 / sha256 / hash_password&verify_password
