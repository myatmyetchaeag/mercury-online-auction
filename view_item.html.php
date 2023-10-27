<?php
    $pageTitle = "Item";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $biddingAmount = "";
    $biddingDate = "";
    $itemNo = "";
    $buyerID = "";
    $itemStage = "";
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    if(isset($_POST['bid'])){
        $biddingAmount = $_POST['biddingAmount'];
        $biddingDate = Date("Y-m-d");
        $itemNo = $_GET['ino'];
        $buyerID = $_SESSION['bid'];
        $itemStage = 'On Bidding';
        
        $statement = $connection->prepare("INSERT INTO buyerBidding(biddingAmount, biddingDate, itemNo, buyerID) 
                    VALUES (?, ?, ?, ?)");
        $statement->bind_param("isis", $biddingAmount, $biddingDate, $itemNo, $buyerID);
        $statement->execute();
        $bidID = $statement->insert_id;
        $statement->close();
        
        $statement = $connection->prepare("UPDATE item SET itemStage = ? WHERE itemNo = ?");
        $statement->bind_param("si", $itemStage, $itemNo);
        $statement->execute();
        $statement->close();
        
        $biddingAmount = "";
        $biddingDate = "";
        $itemNo = "";
        $buyerID = "";
        $itemStage = "";
    }

    if (isset($_GET['ino']) && filter_var($_GET['ino']) ) { 
        $itemNo = $_GET['ino'];
        $statement = $connection->prepare("SELECT count(buyerID) FROM buyerbidding WHERE itemNo = ?");
        $statement->bind_param("i", $itemNo);
        $statement->execute();
        $statement->bind_result($noofbid);
        $statement->fetch();
        $statement->close();
            
	
        
	$statement = $connection->prepare("SELECT i.itemName, i.itemDescription, i.price, i.bidIncrement, i.quantity, i.image, i.validUntil, i.createdDate "
                . "FROM item i WHERE  i.itemNo = ?");
        $statement->bind_param("i", $itemNo);
        $statement->execute();
        $statement->bind_result($itemName, $itemDescription, $price, $bidIncrement, $quantity, $image, $validUntil, $createdDate);
        $statement->fetch();
        $statement->close(); 
        
        $statement = $connection->prepare("SELECT MAX(biddingAmount) FROM buyerbidding WHERE itemNo = ?");
        $statement->bind_param("i", $itemNo);
        $statement->execute();
        $statement->bind_result($biddingAmount);
        $statement->fetch();
        $statement->close();
    }
?>
<form action="" method="post">
    <div><a href="browse_items.html.php" class="button smoothrectangle" style=""><</a></div>
    <div class="eimage"><span></span>
        <a href="images/items/<?php htmlout($image);?>" >
            <img src="images/items/<?php htmlout($image);?>" alt="<?php htmlout($itemName);?>" /></a>
            </br>
            <?php
                $statement = $connection->prepare("SELECT imageID, imageName FROM itemattachment WHERE itemNo = ?");
                $statement->bind_param("i", $_GET['ino']);
                $statement->execute();
                $statement->bind_result($imageID, $imageName);
                while($statement->fetch()){
                    ?>
            <a href="images/items/<?php htmlout($imageName);?>" >
                <img src="images/items/<?php htmlout($imageName); ?>" alt="<?php htmlout($imageName); ?>" style="width: 90px; padding-right: 7px;"></a>
                    <?php
                }
                $statement->close();
            ?>
    </div>
    <div class="desc">
        <h5><?php htmlout($itemName); ?></h5>
    <table>
        <tr class="withoutinput">
            <td><b>Description: </b></td>
            <td><?php htmlout($itemDescription); ?></td>
        </tr>
        <tr class="withoutinput">
            <td><b>Start: </b></td>
            <td><?php htmlout($createdDate); ?></td>
        </tr>
        <tr class="withoutinput">
            <td><b>Ends: </b></td>
            <td><?php htmlout($validUntil); ?></td>
        </tr>
        <tr class="withoutinput">
            <td><b>Starting Bid: </b></td>
            <td>$<?php htmlout($price); ?></td>
        </tr>
        <tr class="withoutinput">
            <td><b>Bid Increment: </b></td>
            <td>$<?php htmlout($bidIncrement); ?></td>
        </tr>
        <tr class="withoutinput">
            <td><b>Quantity: </b></td>
            <td><?php htmlout($quantity); ?></td>
        </tr>
        <tr class="withoutinput">
            <td><b>Bids: </b></td>
            <td><?php htmlout($noofbid);?></td>
        </tr>
        <tr class="withoutinput">
            <td colspan="2">
            <?php 
            $statement = $connection->prepare('SELECT winning, buyerID FROM buyerBidding WHERE itemNo = ? AND winning = 1');
            $statement->bind_param("i", $_GET['ino']);
            $statement->execute();
            $statement->bind_result($winning, $buyerID);
            $statement->fetch();
            $statement->close();
            
            if(isset($_SESSION['bid']) ){
                if($winning == 1){
                    echo '<p>This Item has been sold out!.</p>';
                }else{
                ?>
                <small style="margin-left: 125px; ">Minimum bid $<?php htmlout($price); ?></small>
                <p><b>Your Max bid:</b>
                    <?php if($biddingAmount == 0){?>
                    $<input type="number" style="width: 100px; height: 10px;" name="biddingAmount" value="<?php htmlout($price); ?>">
                    <?php }else{ ?>
                    $<input type="number" style="width: 100px; height: 10px;" name="biddingAmount" value="<?php htmlout($biddingAmount);?>">
                    <?php } ?>
                    <input type="submit" value="Bid" name="bid" class="button">
                </p>
                <?php 
                }
                if($buyerID == $_SESSION['bid']){
                    echo '<p>Congradulations!!! You have won this item!.</p>';
                    echo '<p>Click Here to pay!.</p>';
                }
            }else{
                echo "<p>In order to bid on this item, you must login as buyer first.</p>";
            }
            ?>    
            </td>
        </tr>
    </table>
    </div>
</form>

<?php
    include 'includes/footer.php';
?>