<?php
    //session_start();
    $pageTitle = "Mercury Online Auction";
    include 'includes/header.php';
    require 'includes/db.inc.php';
    
    if(isset($_SESSION['sid'])):
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
?>
    <div id="header_bar">
        <div id="header"><div class="right"></div>
            <h1>
                <img src="images/mercury_logo.jpg" alt="Site Title" />
                <span>Online Auction House</span>
            </h1>    
        </div> 
        <div class="form-header" style="float: left; margin: 30px 0 0 50px;">
            <h2>Your Items</h2>
            
        </div>
        <div style="width: 200px; float: right; margin: 50px 25px 0 50px;">
            <a href="item_register.html.php" class="smoothrectangle" style="background-color: #3a3e5b; color: white; float: right;">Create New Lot</a>
        </div>
    </div>
    <div id="sidebar"><div class="sidebar_top"></div><div class="sidebar_bottom"></div>            
        <div class="sidebar_section">
            <h2>Category</h2>
            <ul class="categories_list">
            <?php 
                
                $statement = $connection->prepare("SELECT * FROM category");
                $statement->execute();
                $statement->bind_result($cid, $cname);
                while ($statement->fetch()) {
                    echo "<li><a href='item_list.html.php?cid=$cid'>$cname</a></li>";
                }
                $statement->close();
                
                echo "<li><a href='item_list.html.php'>All Items</a></li>";
            ?>
            </ul>
            
        </div>
    </div>    
                
    <div id="content">            
    <div class="content-section">
        <div class="product_box_margin_r35"
            <form method="post">
                <div class="content_section">
                    
            <?php
                if(isset($_GET['cid'])){
                    $statement = $connection->prepare("SELECT  itemNo, itemName, price, image, itemStage, categoryID FROM item WHERE categoryID = ? AND sellerID = ?");
                    $statement->bind_param("is", $_GET['cid'], $_SESSION['sid']);
                }else{
                    $statement = $connection->prepare("SELECT itemNo, itemName, price, image, itemStage, categoryID FROM item WHERE sellerID = ?");
                    $statement->bind_param("s", $_SESSION['sid']);
                }
                $statement->execute();
                $statement->bind_result($itemNo, $itemName, $price, $image, $itemStage, $categoryID);
                
                $statement->store_result();
                
                if($statement->num_rows > 0):
                    while($statement->fetch()){
                
                
            ?>
                    <div class="product_box">
                    
                        <h3><?php htmlout($itemName); ?></h3>
    
                        <div class="itemImage"> 
                            <a href="manage_items.html.php?ino=<?php htmlout($itemNo); ?>" target="_parent">
                                <img src="images/items/<?php htmlout($image);?>" alt="<?php htmlout($itemName); ?>" title="<?php htmlout($itemName); ?>"/></a> 
                        </div>
                        <div class="price">
                            <p>Price: $ <?php htmlout($price); ?></p>
                            <p>Item Stage: <?php htmlout($itemStage); ?></p>
                        </div>
                        
                        <a href="manage_items.html.php?ino=<?php htmlout($itemNo); ?>">Edit</a> 
                        
                    </div>
            <?php
                    }
                else:
                    echo "<p style='text-align: center;'>You haven't registered any items yet.</p>"
                    . "<p style='text-align: center;'>If you want to register a new item, click on 'Add New Item'.</p>";
                endif;
            ?>
                    
                </div>    
            </form>
        </div>     
    </div>
    </div>    
    <?php 
    else:
        echo '<p style="text-align: center;">You must login as seller first if you want to view this page.</p>';
    endif;
    include 'includes/footer.php'; ?>
