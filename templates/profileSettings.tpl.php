<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>

<!DOCTYPE html>
<html lang="en-US">
<link href="../css/profileSettings.css" rel="stylesheet">
<script src="../javascript/searchbar.js" defer></script>

<head>
    <title>Edit Profile</title>
    <meta charset="UTF-8">
</head>
 
<?php function drawEditProfileForm($user, $errors, $session){ ?>

<div id="EditProfileContainer">
<div class="profile-section">
    <div class="profile-info">
    <h1>Edit Profile</h1>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><em><?php echo $error; ?></em></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form method="POST" action="../actions/action_editProfile.php">
    <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
    <input type="hidden" name="id" value="<?php echo $user->id; ?>">
        <div class="info-item">
            <label for="username">Username</label>
            <input minlength="3" maxlength="20" type="text" value="<?php echo $user->username; ?>"
            id="username" name="username">
        </div>
        <div class="info-item">
            <label for="firstName">First Name</label>
            <input type="text" value="<?php echo $user->firstName; ?>"
            id="firstName" name="firstName" minlength="3" maxlength="20">
        </div>
        <div class="info-item">
            <label for="lastName">Last Name</label>
            <input type="text" value="<?php echo $user->lastName; ?>"
            id="lastName" name="lastName" minlength="3" maxlength="20">
        </div>
        <button type="submit">Apply Changes</button>
    </div>
</div>
</div>
<?php } ?>

<?php function drawEditCredentialsForm($user){ ?>

<div id="EditProfileContainer">
<div class="profile-section" id="change">
    <div class="profile-info">
    <h1>Manage Credentials</h1>
    <input type="hidden" name="id" value="<?php echo $user->id; ?>">

    <a href="../pages/profileSettingsEmail.php?username=<?php echo $user->username?>">
        <div class="info-item">
            <label for="email">Change E-mail</label></div></a>
        <a href="../pages/profileSettingsPassword.php?username=<?php echo $user->username?>">
        <div class="info-item">
            <label for="oldPassword">Change Password</label>
        </div></a>
        <a href="../pages/profileSettingsDelete.php?username=<?php echo $user->username?>">
        <div class="info-item">
            <label for="oldPassword">Delete Account</label>
        </div></a>
    </div>
</div>
</div>

<?php } ?>

<?php function drawEditEmailForm($user, $errors, $session){ ?>

<div id="EditProfileContainer">
<div class="profile-section">
    <div class="profile-info">
    <h1>Change Email</h1>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><em><?php echo $error; ?></em></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form method="POST" action="../actions/action_editEmail.php">
    <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
    <input type="hidden" name="id" value="<?php echo $user->id; ?>">
        <div class="info-item">
            <label for="newEmail">New E-mail</label>
            <input type="email"
            id="newEmail" name="newEmail">
        </div>
        <div class="info-item">
            <label for="newpassword">Confirm new E-mail</label>
            <input type="email"
            id="repeatEmail" name="repeatEmail">
        </div>
        <div class="info-item">
            <label for="Password">Password</label>
            <input type="password"
            id="Password" name="Password">
        </div>
        <button type="submit">Apply Changes</button>
    </div>
</div>
</div>

<?php } ?>

<?php function drawEditPasswordForm($user, $errors, $session){ ?>

<div id="EditProfileContainer">
<div class="profile-section">
    <div class="profile-info">
    <h1>Change Password</h1>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><em><?php echo $error; ?></em></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form method="POST" action="../actions/action_editPassword.php">
    <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
    <input type="hidden" name="id" value="<?php echo $user->id; ?>">
        <div class="info-item">
            <label for="oldPassword">Current Password</label>
            <input type="password"
            id="oldPassword" name="oldPassword">
        </div>
        <div class="info-item">
            <label for="newpassword">New Password</label>
            <input type="password"
            id="newPassword" name="newPassword">
        </div>
        <div class="info-item">
            <label for="repeatPassword">Repeat Password</label>
            <input type="password"
            id="repeatPassword" name="repeatPassword">
        </div>
        <button type="submit">Apply Changes</button>
    </div>
</div>
</div>

<?php } ?>

<?php function drawDeleteForm($user, $errors, $session){ ?>

<div id="EditProfileContainer">
<div class="profile-section">
    <div class="profile-info">
    <h1>Delete Account</h1>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><em><?php echo $error; ?></em></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form method="POST" action="../actions/action_deleteAccount.php">
    <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
    <input type="hidden" name="id" value="<?php echo $user->id; ?>">
        <div class="info-item">
            <label for="password">Password</label>
            <input type="password"
            id="password" name="password">
        </div>
        <div class="info-item">
            <label for="repeatPassword">Repeat Password</label>
            <input type="password"
            id="repeatPassword" name="repeatPassword">
        </div>
        <button id="delete" type="submit">DELETE ACCOUNT</button>
    </div>
</div>
</div>

<?php } ?>

<?php function drawSideMenu($db,$session){ 
    $pagina = basename($_SERVER['PHP_SELF']);
    ?>

    <div id="sideOptions">
        <h1>Settings</h1>
        <ul>
            <a href="profileSettings.php?username=<?php echo user::getUserById($db,$session->getId())->username; ?>" 
            <?php if($pagina == 'profileSettings.php') echo 'class="active"'; ?>>
            <li>Profile Settings</li></a>

            <a href="accountSettings.php?username=<?php echo user::getUserById($db,$session->getId())->username; ?>"
            <?php if($pagina == 'accountSettings.php') echo 'class="active"'; ?>>
            <li>Account Settings</li></a>

            <a href="profileSettingsOrderHistory.php?username=<?php echo user::getUserById($db,$session->getId())->username; ?>"
            <?php if($pagina == 'profileSettingsOrderHistory.php') echo 'class="active"'; ?>>
            <li>Order History</li></a>

            <a href="profileSettingsSalesHistory.php?username=<?php echo user::getUserById($db,$session->getId())->username; ?>"
            <?php if($pagina == 'profileSettingsSalesHistory.php') echo 'class="active"'; ?>>
            <li>Sales History</li></a>
        </ul>
    </div>

<?php } ?>


<?php
function drawOrderHistory($db, $session, $products, $groupedProducts,$admin) {
    krsort($groupedProducts);
    ?>
    <section id="orderHistory" class="orderHistoryContainer">
        <div class="inlineContain">
            <div class="topFlex" id="first">
                <h1>Order History</h1>
            </div>
            <div class="overflowContainer">
                <div class="productGroup">
                    <?php 
                    foreach ($groupedProducts as $groupNumber => $productsInGroup) {
                        $totalProducts = 0;
                        $totalAmount = 0;
                        ?>
                        <div class="listingWithTitle">
                            <div class="orderDiv">Order <?= $groupNumber ?>:</div>
                            <?php if ($admin){ ?>
                                <p class="orderDiv" onclick="window.location.href='../pages/profile.php?username=<?php echo user::getUserById($db, order::getBuyerIDByGroup($db, $groupNumber))->username ?>'">Buyer: <span id="productSeller" style="cursor:pointer"><?php echo user::getUserById($db, order::getBuyerIDByGroup($db, $groupNumber))->username ?></span></p> <?php } ?>                            <?php
                            foreach ($productsInGroup as $product) {
                                $totalProducts++;
                                $totalAmount += $product->price;
                                ?>
                                <li class="productList"> 
                                    <div class="productInfo">
                                        <div id="imgproduct">
                                            <img src="../<?= $product->imageUrl ?>" alt="<?= $product->name ?>">
                                        </div>
                                        <div class="infoList">
                                            <div><?= $product->name ?></a></div>
                                            <div><?= $product->category ?></div>
                                            <div class="priceInfo">$<?= $product->price ?></div>
                                            <div id="productSeller">
                                                Sold by 
                                                <a id="productSeller" href="profile.php?username=<?=user::getUserById($db,$product->SellerID)->username?>">
                                                    <?php 
                                                    if(user::getUserById($db,$product->SellerID) == null) {
                                                        echo "Deleted User";
                                                    } else {
                                                        echo (user::getUserById($db, $product->SellerID))->firstName . ' ' . (user::getUserById($db, $product->SellerID))->lastName;
                                                    }
                                                    ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                            ?>
                            <div class="orderFlex">
                            <div class="orderSummary">
                                <h3>Order Summary:</h3>
                                <p>Products Bought: <?= $totalProducts ?></p>
                                <p>Shipping:
                                    <?php 
                                        $shipping = order::getOrderByGroup($db, $groupNumber)[0]->shipping;
                                        $company = '';
                                        switch ($shipping) {
                                            case 2:
                                                $company = 'Economy Shipping';
                                                break;
                                            case 5:
                                                $company = 'Standard Shipping';
                                                break;
                                            case 10:
                                                $company = 'Express Delivery';
                                                break;
                                            default:
                                                $company = 'Unknown Company';
                                        }
                                        echo '$' . ($shipping * count($productsInGroup)) . ' (' . $company . ')';                ?>
                                </p>
                                <p>Money Spent: $<?= $totalAmount + $shipping * count($productsInGroup) ?></p>
                            </div>
                            <?php if(!$admin){ ?>
                                    <button class="printForm" onclick="printShippingForm(<?= $groupNumber ?>)">Print Shipping Form</button> <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <?php foreach ($groupedProducts as $groupNumber => $productsInGroup) { ?>
        <div id="shippingForm<?= $groupNumber ?>" style="display:none">
            <h3>Shipping Form</h3>
            <h3>Order <?= $groupNumber ?>:</h3>
            <div class="watermark">Vintech</div>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Seller</th>
                </tr>
                <?php foreach ($productsInGroup as $product) { ?>
                    <tr>
                        <td><?= $product->name ?></td>
                        <td>$<?= $product->price ?></td>
                        <td>
                            <?php 
                            if(user::getUserById($db,$product->SellerID) == null) {
                                echo "Deleted User";
                            } else {
                                echo (user::getUserById($db, $product->SellerID))->firstName . ' ' . (user::getUserById($db, $product->SellerID))->lastName;
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <p>Shipping:
                <?php 
                    $shipping = order::getOrderByGroup($db, $groupNumber)[0]->shipping;
                    $company = '';
                    switch ($shipping) {
                        case 2:
                            $company = 'Economy Shipping';
                            break;
                        case 5:
                            $company = 'Standard Shipping';
                            break;
                        case 10:
                            $company = 'Express Delivery';
                            break;
                        default:
                            $company = 'Unknown Company';
                    }
                    echo '$' . ($shipping * count($productsInGroup)) . ' (' . $company . ')';                ?>
            </p>
            <p>Total Price: $<?= $totalAmount + $shipping * count($productsInGroup) ?></p>
            <!--<p>Paying Method: </p>--> 
        </div>
    <?php } ?>
    <?php
    }
    ?>

<?php
function drawSalesHistory($db, $session, $products, $groupedProducts) {
    krsort($groupedProducts);
    ?>
    <section id="orderHistory" class="orderHistoryContainer">
        <div class="inlineContain">
            <div class="topFlex" id="first">
                <h1>Sales History</h1>
            </div>
            <div class="overflowContainer">
                <div class="productGroup">
                    <?php 
                    foreach ($groupedProducts as $groupNumber => $productsInGroup) {
                        $totalProducts = 0;
                        $totalAmount = 0;
                        ?>
                        <div class="listingWithTitle">
                            <div class="orderDiv">Order <?= $groupNumber ?>:</div>
                            <?php
                            foreach ($productsInGroup as $product) {
                                $totalProducts++;
                                $totalAmount += $product->price;
                                ?>
                                <li class="productList"> 
                                    <div class="productInfo">
                                        <div id="imgproduct">
                                            <img src="../<?= $product->imageUrl ?>" alt="<?= $product->name ?>">
                                        </div>
                                        <div class="infoList">
                                            <div><?= $product->name ?></a></div>
                                            <div><?= $product->category ?></div>
                                            <div class="priceInfo">$<?= $product->price ?></div>
                                            <?php
                                                $order = Order::getOrderBySellerIdAndProductId($db, $product->SellerID, $product->id);
                                                if ($order) {
                                                ?>
                                                <div id="productBuyer">
                                                    Bought by 
                                                    <a id="productBuyer" href="profile.php?id=<?=user::getUserById($db,$order->buyerID)->username?>">
                                                        <?php 
                                                        $buyer = user::getUserById($db, $order->buyerID);
                                                        if($buyer == null) {
                                                            echo "Deleted User";
                                                        } else {
                                                            echo $buyer->firstName . ' ' . $buyer->lastName;
                                                        }
                                                        ?>
                                                    </a>
                                                </div>
                                                <?php
                                                }
                                                ?>                                    
                                        </div>
                                </li>
                                <?php
                            }
                            ?>
                            <div class="orderFlex">
                            <div class="orderSummary">
                                <h3>Order Summary:</h3>
                                <p>Products Sold: <?= $totalProducts ?></p>
                                <p>Money Earned: $<?= $totalAmount ?></p>
                            </div>
                            <button class="printForm" onclick="printShippingForm(<?= $groupNumber ?>)">Print Shipping Form</button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php foreach ($groupedProducts as $groupNumber => $productsInGroup) { ?>
        <div id="shippingForm<?= $groupNumber ?>" style="display:none">
            <h3>Shipping Form</h3>
            <h3>Order <?= $groupNumber ?>:</h3>
            <div class="watermark">Vintech</div>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Buyer</th>
                </tr>
                <?php 
                $totalAmount = 0;
                foreach ($productsInGroup as $product) { 
                    $totalAmount += $product->price;
                    $order = Order::getOrderBySellerIdAndProductId($db, $product->SellerID, $product->id);
                    $buyer = $order ? user::getUserById($db, $order->buyerID) : null;
                ?>
                    <tr>
                        <td><?= $product->name ?></td>
                        <td>$<?= $product->price ?></td>
                        <td>
                            <?php 
                            if($buyer == null) {
                                echo "Deleted User";
                            } else {
                                echo $buyer->firstName . ' ' . $buyer->lastName;
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <p>Total Amount Earned: $<?= $totalAmount ?></p>
        </div>
    <?php } ?>

    <?php
}
?>


</body>

<script>

function printShippingForm(groupNumber) {
    var shippingForm = document.getElementById("shippingForm" + groupNumber);
    var header = document.getElementById("fixedHeader");
    header.style.display = 'none';
    var sideOpt = document.getElementById("sideOptions");
    sideOpt.style.display = 'none';
    var container = document.getElementById("orderHistory");
    container.style.display = 'none';

    shippingForm.style.display = 'block';
    shippingForm.classList.add('print');
    window.print();

    shippingForm.style.display = 'none';
    header.style.display = 'block';
    header.style.position = 'fixed';
    header.style.removeProperty('display');
    sideOpt.style.display = 'block';
    container.style.display = 'block';
    
}

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