<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>

<!DOCTYPE html>
<html lang="en-US">
<link href="../css/landingPage.css" rel="stylesheet">
<link href="../css/admin.css" rel="stylesheet">
<link href="../css/submitProduct.css" rel="stylesheet">
<script src="../javascript/searchbar.js" defer></script>
<script src="../javascript/addToCart.js" defer></script>
<script src="../javascript/upload.js"></script>

<head>
    <title>Admin Page</title>
    <meta charset="UTF-8">
</head>

<bo>
    <?php function drawAdminPage($session, $user, $db){?>

    <section class="adminContainer">

        <div class="adminTitle">Admin Page</div>

        <div class="adminOptions">
            <div class="adminOption" id="addCategory">
                <a href="addCategory.php">Category</a>
            </div>
            <div class="adminOption" id="addUser">
                <a href="addUser.php">User</a>
            </div>
            <div class="adminOption" id="viewOrders">
                <a href="viewOrders.php">Orders</a>
            </div>
        </div>

    </section>


    <?php } ?>


    <?php function drawSideMenuAdmin($session, $db){
        $pagina = basename($_SERVER['PHP_SELF']) . '?' . $_SERVER['QUERY_STRING'];?>
        

        <div id="sideOptions">
            <h1>View</h1>
            <ul>
                <a href="admin.php?selector=1"
                <?php if($pagina == 'admin.php?selector=1') echo 'class="active"'; ?>>
                <li>User</li></a>
                
                <a href="admin.php?selector=2"
                <?php if($pagina == 'admin.php?selector=2') echo 'class="active"'; ?>>  
                <li>Orders</li></a>

                <a href="admin.php?selector=3"
                <?php if($_GET['selector'] >= 3) echo 'class="active"'; ?>>    
                <li>Caracteristics</li></a>

                <?php if($_GET['selector'] >= 3){?>
                    
                    <a href="admin.php?selector=3"
                    <?php if($pagina == 'admin.php?selector=3') echo 'class="active"'; ?>>    
                    <li style="margin-left:1em">Category</li></a>

                    <a href="admin.php?selector=4"
                    <?php if($pagina == 'admin.php?selector=4') echo 'class="active"'; ?>>    
                    <li style="margin-left:1em">Size</li></a>

                    <a href="admin.php?selector=5"
                    <?php if($pagina == 'admin.php?selector=5') echo 'class="active"'; ?>>    
                    <li style="margin-left:1em">Condition</li></a>
                    
                <?php } ?> 
            </ul>
        </div>

    <?php } ?>

    <?php function drawUsersAdmin($session, $user, $db){
        $users = User::getAll($db); ?>

<div class="adminContainer">
    <div class="Container">
        <?php foreach($users as $user){ ?>
            <div class="user">
                <div class="info">
                    <div class="row">
                        <div class="userID">ID: <?php echo $user->id; ?></div>
                        <div class="username">Username: <?php echo $user->username; ?></div>
                    </div>
                    <div class="row">
                        <div class="email">Email: <?php echo $user->email; ?></div>
                        <div class="rank">Rank: <?php echo $user->rank == 0 ? "usuário" : "admin"; ?> </div>
                    </div>
                </div>
                <div class="actions">
                    <div class="lock">
                        <button class="lockButton" onclick="window.location.href='../pages/profile.php?username=<?php echo $user->username ?>'">Check Profile</button>
                    </div>
                    <div class="rank">
                        <?php if($user->rank == 0) { ?>
                            <form action="../actions/action_rankUser.php" method="post">
                                <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
                                <input type="hidden" name="username" value="<?php echo $user->username ?>">
                                <button class="rankUpButton" type="submit">Promote to Admin</button>
                            </form>
                      <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

    <?php } ?>


    <?php function drawOrdersAdmin($session, $user, $db){
        $products = Order::getProcessedOrders($db);
        $groupedProducts = Order::mapProductsToOrderGroups($db, $products);
        $admin = true;
        $text = "";
        drawOrderHistory($db, $session, $products, $groupedProducts,$admin);
        ?>


    <?php } ?>

    <?php function drawCaracteristicsAdmin($session, $user, $db){
        $selector = $_GET['selector'];
        if($selector == 3){
            $selector = 'Categories';
            $text = "Category";
        }
        else if($selector == 4){
            $selector = 'Tamanho';
            $text = "Size";
        }
        else if($selector == 5){
            $selector = 'Condition';
            $text = "Condition";
        }

        $caracteristics = Caracteristicas::getCaracteristicasByType($db, $selector); 
        ?>

        <div class="adminContainer" data-value="<?php echo $selector; ?>">
            <div class="Container">

                <div class="header">
                    <h1><?php echo $text ?></h1>
                    <button onclick="enableUploads()" class="addButton">Add</button>
                </div>
            

                <div class="addForm" style="display: none;">
                    <?php if($selector == 'Categories'): ?>    
                        <form id="uploadForm" method="POST" action="../actions/action_upload.php" enctype="multipart/form-data">
                            <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
                            <div id="infoItem" class="info-item">
                                <label id="imageLabel" for="image">Product Image</label>
                                <input style="display: none;" type="file" id="image" name="image">
                                <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
                                <label for="image" class="uploadButton">Upload</label>
                                <button style="display:none" id="uploadButton" class="uploadButton" type="submit">Upload</button>
                            </div>
                        </form>
                    <?php endif; ?>
                
                
                    <form action="../actions/action_addCaracteristic.php" method="post">
                    
                        <label for="carateristicasType">Type:</label>
                        <p id="carateristicasType"><?php echo $text; ?></p>
                        <input type="hidden" name="carateristicasType" value="<?php echo $selector; ?>">
                        <label for="carateristicasValue">Value:</label>
                        <input type="text" id="carateristicasValue" name="carateristicasValue">
                        <?php if($selector == 'Categories'){ ?>
                            <input type="hidden" id="imageUrl" name="imageUrl">
                        <?php } 
                        else { ?>
                            <input type="hidden" name="imageUrl" value="<?php echo 'assets/placeholder-1-1.webp'; ?>">
                        <?php } ?>
                        
                        <input type="hidden" name="selector" value="<?php echo $_GET['selector']; ?>">
                        <input type="submit" value="Add">
                        <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
                        <script>
                            document.querySelector('input[type="submit"]').addEventListener('click', function(event) {
                                var text = document.getElementById('carateristicasValue').value; 
                                var imageUrlElement = document.getElementById('imageUrl');
                                var selector = document.querySelector('.adminContainer').getAttribute('data-value');                                
                                console.log(imageUrlElement);
                                if (text === '') {
                                    alert('Please input text.');
                                    event.preventDefault();
                                }
                                console.log(selector);
                                console.log(imageUrlElement.value);
                                if (selector == 'Categories' && imageUrlElement && imageUrlElement.value === '') {
                                    alert('Please input an image URL.');
                                    event.preventDefault();
                                }
                            });
                        </script>
                    </form>
                </div>

                <script>
                    document.querySelector('.addButton').addEventListener('click', function() {
                        var form = document.querySelector('.addForm');
                        var button = document.querySelector('.addButton');
                        if (form.style.display === 'none') {
                            form.style.display = 'block';
                            button.textContent = 'Cancel';
                        } else {
                            form.style.display = 'none';
                            button.textContent = 'Add';
                        }
                    });
                </script>

<?php foreach($caracteristics as $caracteristic) { ?>
    <div class="caracteristicTile">
        <?php if($selector == 'Categories'): ?>
            <img class="limited-img" src="<?php echo "../".$caracteristic->caracImg; ?>" alt="Category Image">
        <?php endif; ?>
        <div class="caracteristicWrapper">
            <div class="caracteristicInfo">
                <div class="caracteristicId">Id: <?php echo $caracteristic->caracID; ?></div>
                <div class="caracteristicName">Name: <?php echo $caracteristic->caracValue; ?></div>
            </div>
        </div>
        <div class="tileButtons">
            <button class="editButton" data-image-url="<?php echo $caracteristic->caracImg?>" data-id='<?php echo $caracteristic->caracID ?>' data-value="<?php echo $caracteristic->caracValue ?>" data-type="<?php echo $caracteristic->caracType ?>" data-selector="<?php echo $_GET['selector'] ?>" data-image-url="<?php $caracteristic->caracImg; ?>">Edit</button>
            <form action="../actions/action_deleteCaracteristic.php" method="post">
                <input type="hidden" name="csrf" value="<?php echo $session->getCsrf(); ?>">
                <input type="hidden" name="caracteristicaID" value="<?php echo $caracteristic->caracID; ?>">
                <input type="hidden" name="selector" value="<?php echo $_GET['selector']; ?>">
                <button type="submit" class="tileButton">Delete</button>
            </form>
        </div>
    </div>
<?php } ?>

            </div>
        </div>
        <script>
            var text = "<?php echo $text; ?>";
            var selector = "<?php echo $selector; ?>";
            var csrf = "<?php echo $session->getCsrf(); ?>";
            var selectorGet = "<?php echo $_GET['selector']; ?>";
            var caracteristicaID = "<?php echo $caracteristic->caracID; ?>";
        </script>
        <script>
            var csrf = "<?php echo $session->getCsrf(); ?>";
            var id = "<?php echo $caracteristic->caracID; ?>";
            
            window.onload = function() {
                var buttons = document.querySelectorAll('.editButton');
                buttons.forEach(function(button, index) {
                button.addEventListener('click', function() {
                var caracteristicaID = this.getAttribute('data-id');
                var caracteristicaValue = this.getAttribute('data-value');
                var caracteristicaType = this.getAttribute('data-type');
                var caracteristicaSelector = this.getAttribute('data-selector'); 
                var caracteristicaImg = this.getAttribute('data-image-url');   
                console.log(caracteristicaID);
                var modal = document.createElement('div');
                var caracteristicaImg = this.getAttribute('data-image-url');
                
                modal.style.position = 'fixed';
                modal.style.top = '0';
                modal.style.left = '0';
                modal.style.width = '100%';
                modal.style.height = '100%';
                modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                modal.style.display = 'flex';
                modal.style.justifyContent = 'center';
                modal.style.alignItems = 'center';
                
            
                modal.innerHTML = `
                <form class="myEditForm" action="../actions/action_editCaracteristic.php" method="post">
                    <label for="textField">Text Field:</label>
                    <input type="hidden" name="csrf" value="${csrf}">
                    <input type="hidden" name="editcaracteristicaID" value="${caracteristicaID}">
                    <input type="hidden" name="editCaracteristicType" value="${caracteristicaType}">
                    <input type="hidden" name="imageUrl" value="${caracteristicaImg}">
                    <input type="hidden" name="selector" value="${caracteristicaSelector}">
                    <input type="text" id="textField" name="editCaracteristicValue" placeholder="${caracteristicaValue}">
                    <div class="buttonWrapper">
                        <input type="submit" value="Submit">
                    </div>
                </form>
                `;


                

            document.body.appendChild(modal);
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
                    });
                });
            });
        };
                
    </script>

    <?php } ?>

    <script>
        enableUploads();
    </script>
  
