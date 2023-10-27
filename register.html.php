<?php

    $pageTitle = "Registration";
    include 'includes/header.php';
    
    if(isset($_POST['sellerReg'])){
        header("Location: seller_register.html.php");
    }
    
    if(isset($_POST['buyerReg'])){
        header("Location: buyer_register.html.php");
    }
?>

            
            <form method="post">
            <div class="buyerReg">
                <h3 class="text-primary">Basic Buyer</h3>
                <h4 class="text-success">Free</h4>
                <strong>Bid on auctions</strong>
                <p style="color: #777; font-size: 85%; ">Shop for and bid on any auction limited up to $1,000 per day</p>
                <input type="submit" name="buyerReg" value="REGISTER" class="button smoothrectangle" style="margin-bottom: 10px;">
            </div>

            <div class="sellerReg">
                <h3 class="text-primary">Seller</h3>
                <h4 class="text-success">$8.00 Monthly</h4>
                <strong>Bid on auctions</strong>
                <p style="color: #777; font-size: 85%; ">Classic auctions with optional minimum bid and reserve price, 7.5% of your item price has to be paid to the Auctioneer as item registration fee, Buy Now/ Fixed Price auctions</p>
                <input type="submit" name="sellerReg" value="REGISTER" class="button smoothrectangle" style="margin-bottom: 10px;">
            </div>
            </form>
    <?php include 'includes/footer.php';?>

