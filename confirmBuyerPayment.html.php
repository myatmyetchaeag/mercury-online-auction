<?php
    $pageTitle = "Confirm Payment";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    $paymentAmount = '';
    $paymentDate = '';
    $paymentType = '';
    $itemNo = '';
    $sellerID = '';
    $AucID = '';
    
    if(isset($_POST['confirm'])){
        $paymentAmount = $_POST['paymentamount'];
        $paymentDate = $_POST['paymentdate'];
        
        $buyerID = $_POST['buyerid'];
        $sellerID = $_SESSION['sid'];
        
        $itemNo = $_POST['itemno'];
        $itemStage = "Paid";
        $paymentStatus = 1;
        
        
        $statement = $connection->prepare('INSERT INTO buyerPayment(`paymentAmount`, `paymentDate`, `sellerID`, `buyerID`, `itemNo`) '
            . 'VALUES(?, ?, ?, ?)');
        $statement->bind_param("dsssi", $paymentAmount, $paymentDate, $sellerID, $buyerID, $itemNo);
        $statement->execute();
        $paymentID = $statement->insert_id;
        $err1 = $statement->error;
        $statement->close();
        
        
        $statement = $connection->prepare("UPDATE item SET itemStage = ? WHERE itemNo = ?");
        $statement->bind_param("si", $itemStage, $itemNo);
        $statement->execute();
        $err2 = $statement->error;
        $statement->close();

        $statement = $connection->prepare("UPDATE BuyerBidding SET paymentStatus = ? WHERE itemNo = ? AND winning = 1;");
        $statement->bind_param("si", $paymentStatus, $itemNo);
        $statement->execute();
        $err3 = $statement->error;
        $statement->close();
            
        if (empty($err1) && empty($err2) && empty($err3)) {
            echo "<script>alert('PAYMENT CONFIRMED'); location.assign('');</script>";
        }else{
            echo "<script>alert('CONFRIMATION ERROR.');</script>";
        }
            
        
    }
    
    
    if(isset($_SESSION['sid'])):
    
    include 'includes/s_activities.html';
?>

<div class="user-form">
    <form action="" method="POST" name="form">
    <div class="text">BUYER:</div>
    <select name="buyerid" required>
        <option value="">SELECT BUYER</option>
        <?php 
            $statement = $connection->prepare("SELECT DISTINCT b.BuyerID, b.BuyerName
                        FROM Buyer b, BuyerBidding bb, Item i, Seller s
                        WHERE s.SellerID = ?
                        AND b.BuyerID = bb.BuyerID
                        AND bb.ItemNo = i.ItemNo
                        AND i.SellerID = s.SellerID
                        AND i.itemStage = 'Sold' AND bb.winning = 1;");
            $statement->bind_param("s", $_SESSION['sid']);
            $statement->execute();
            $statement->bind_result($bid, $bname);
            while ($statement->fetch()) {
                $s = ($buyerID == $bid)? "selected": "";
                echo "<option value='$bid' $s>$bname</option>";
            }
        ?>

    </select>
       
    <div class="text">Item: </div>
        <select name="itemno" required>
            <option value="">SELECT ITEM</option>
            <?php 
                $statement = $connection->prepare("SELECT i.itemNo, i.itemName
                            FROM item i, seller s
                            WHERE i.sellerID = s.sellerID
                            AND s.sellerID = ? AND i.itemStage = 'Sold'");
                $statement->bind_param("s", $_SESSION['sid']);
                $statement->execute();
                $statement->bind_result($ino, $iname);
                while ($statement->fetch()) {
                    $s = ($itemNo == $ino)? "selected": "";
                    echo "<option value='$ino' $s>$iname</option>";
                }
            ?>

        </select>

    <div class="text">PAYMENT AMOUNT:</div>
    <input type="text" name="paymentamount" value="<?php htmlout($paymentAmount); ?>" maxlength="10" required pattern="[0-9.]+" 
           placeholder="Payment Amount" autofocus title="Enter the amount of the payment in decimal or integer"/>

    <div class="text">PAYMENT DATE:</div>
    <input type="text" data-format="yyyy-MM-dd" name="paymentdate" id="date" maxlength="11" value="<?php htmlout($paymentDate); ?>"
               placeholder="Payment Date" title="Enter the date of the payment"/>


    

    <input type="submit" name="confirm" value="Confirm" class="button smoothrectangle"/>
    <input type="submit" name="cancel" value="Cancel" formnovalidate class="button smoothrectangle"/>
    </form>
</div>
<?php 
    else:
        echo '<p style="text-align: center;">You must login as seller first if you want to view this page.</p>';
    endif;    
    include 'includes/footer.php';
?>