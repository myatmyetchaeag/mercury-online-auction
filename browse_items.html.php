<?php
    
    $pageTitle = "Welcome to Mercury Online Auction!!!";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
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
            <h2>Featured Items</h2>
        </div>
    </div>
    <div id="sidebar">
        
        <div class="sidebar_top"></div><div class="sidebar_bottom"></div>            
        <div class="sidebar_section">
            <h2>Category</h2>
            <ul class="categories_list">
            <?php 
                
                $statement = $connection->prepare("SELECT * FROM category");
                $statement->execute();
                $statement->bind_result($ctid, $ctname);
                while ($statement->fetch()) {
                    echo "<li><a href='browse_items.html.php?id=$ctid'>$ctname</a></li>";
                }
                $statement->close();
                echo "<li><a href='browse_items.html.php'>All Items</a></li>";
            ?>
            </ul>
        </div>
    </div>    
    <div id="content">            
    <div class="content-section">
        <div class="product_box_margin_r35">
            <form method="post">
                <div class="content_section">
                    
            <?php
                if(isset($_GET['id'])){
                    $statement = $connection->prepare("SELECT  itemNo, itemName, price, image, categoryID FROM item WHERE categoryID = ? AND itemStage != 'Registered' AND itemStage != 'Paid' AND itemStage != 'Closed'");
                    $statement->bind_param("i", $_GET['id']);
                }else{
                    $statement = $connection->prepare("SELECT itemNo, itemName, price, image, categoryID FROM item WHERE itemStage != 'Registered' AND itemStage != 'Paid' AND itemStage != 'Closed'");
                }
                $statement->execute();
                $statement->bind_result($itemNo, $itemName, $price, $image, $categoryID);
                while($statement->fetch()){
                
                
            ?>
                    <div class="product_box">
                        <h3><?php htmlout($itemName); ?></h3>
    
                        <div class="itemImage"> 
                            <a href="view_item.html.php?ino=<?php htmlout($itemNo); ?>" target="_parent">
                                <img src="images/items/<?php htmlout($image);?>" alt="<?php htmlout($itemName); ?>" title="<?php htmlout($itemName); ?>"/></a> 
                        </div>
                        
                        <p class="price">Price: $ <?php htmlout($price); ?></p>
                        <a href="view_item.html.php?ino=<?php htmlout($itemNo); ?>">Detail</a> 
                        
                    </div>
    <?php
                }
            ?>
                    
                </div>  
            </form>
        </div>     
    </div>
    </div>    
    <?php include 'includes/footer.php'; ?>
