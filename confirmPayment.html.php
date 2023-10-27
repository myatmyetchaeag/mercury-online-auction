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
        $paymentType = $_POST['paymenttype'];
        
        
        $sellerID = $_POST['sellerid'];
        $AucID = $_SESSION['aid'];
        
        
        $statement = $connection->prepare('INSERT INTO `sellerpayment`(`paymentAmount`, paymentDate, paymentType, AucID, sellerID) '
                . 'VALUES (?, ?, ?, ?, ?)');
        $statement->bind_param("dssis", $paymentAmount, $paymentDate, $paymentType, $AucID, $sellerID);
        $statement->execute();
        $paymentID = $statement->insert_id;
        $error = $statement->error;
        $statement->close();
               
        if (empty($error)) {
            $regFeePaid = 1;
            $statement = $connection->prepare("UPDATE seller "
                    . "SET regFeePaid = ?,  regPayDueDate = DATE_ADD(regPayDueDate, INTERVAL 1 MONTH)"
                    . "WHERE sellerID = ?");
            $statement->bind_param("is", $regFeePaid, $sellerID);
            $statement->execute();
            $statement->close();
            
            echo "<script>alert('PAYMENT CONFIRMED'); location.assign('report.html.php');</script>";
              
        }else{
            $categoryID = "";
            echo "<script>alert('CONFRIMATION ERROR.');</script>";
        }
        
        if($paymentType == "Item Percentage"){   
            $itemNo = $_POST['itemno'];
            
            $statement = $connection->prepare('SELECT bidID, biddingamount
                        FROM buyerbidding b
                        WHERE b.itemNo = ?
                        AND biddingAmount in (SELECT max(biddingAmount) FROM buyerbidding WHERE itemNo = b.itemNo);');
            $statement->bind_param("i", $itemNo);
            $statement->execute();
            $statement->bind_result($bidID, $maxbid);
            $statement->fetch();
            $statement->close();
            
            $cPaid = 1;
            $statement = $connection->prepare('UPDATE buyerbidding SET commissionPaid = ? WHERE bidID = ?');
            $statement->bind_param("ii", $cPaid, $bidID);
            $statement->execute();
            $statement->close(); 
            
            $statement = $connection->prepare('INSERT INTO payment(`paymentAmount`, `paymentDate`, `paymentType`, `AucID`, `sellerID`, `itemNo`) '
                . 'VALUES(?, ?, ?, ?, ?, ?)');
            $statement->bind_param("dssisi", $paymentAmount, $paymentDate, $paymentType, $AucID, $sellerID, $itemNo);
            $statement->execute();
            $paymentID = $statement->insert_id;
            $statement->close();   
        }
            
        
    }
        
    if(isset($_POST['sellerid'])){
        
        $statement = $connection->prepare("SELECT sellerName FROM seller WHERE sellerID = ?");
        $statement->bind_param("s", $_POST['sellerid']);
        $statement->execute();
        $statement->bind_result($SNAME);
        $statement->fetch();
        $statement->close();
        
    }
    
    if(isset($_POST['cancel'])){
        header("Location: choosePaymentType.html.php");
    }
    
    include 'includes/a_activities.html';
?>
<div class="user-form">
    <form action="" method="POST" name="form">
    <div class="text">PAYMENT TYPE:</div>
    <input type="text" name="paymenttype" value="<?php htmlout($_POST['paymenttype']); ?>" readonly=""/>

    <div class="text">SELLER:</div>
    <input type="hidden" name="sellerid" value="<?php htmlout($_POST['sellerid']); ?>"/>
    <input type="text" name="" value="<?php htmlout($SNAME); ?>" readonly=""/>
        
<?php if(isset($_POST['paymenttype']) && $_POST['paymenttype'] == "Registration Fee"){ ?>
            <div class="text">PAYMENT AMOUNT:</div>
            <input type="text" name="paymentamount" value="<?php htmlout($paymentAmount); ?>" maxlength="10" required pattern="[0-9.]+" 
                   placeholder="Payment Amount" autofocus title="Enter the amount of the payment in decimal or integer"/>

            <div class="text">PAYMENT DATE:</div>
            <input type="text" data-format="yyyy-MM-dd" name="paymentdate" id="date" maxlength="11" value="<?php htmlout($paymentDate); ?>"
                       placeholder="Payment Date" title="Enter the date of the payment"/>

            <input type="submit" name="confirm" value="Confirm" class="button smoothrectangle"/>
            <input type="submit" name="cancel" value="Cancel" formnovalidate class="button smoothrectangle"/>
        <?php }elseif(isset($_POST['paymenttype']) && $_POST['paymenttype'] == "Item Percentage"){?>
            <div class="text">PAYMENT AMOUNT:</div>
            <input type="text" name="paymentamount" value="<?php htmlout($paymentAmount); ?>" maxlength="10" required pattern="[0-9.]+" 
                   placeholder="Payment Amount" autofocus title="Enter the amount of the payment in decimal or integer"/>

            <div class="text">PAYMENT DATE:</div>
            <input type="text" data-format="yyyy-MM-dd" name="paymentdate" id="date" maxlength="11" value="<?php htmlout($paymentDate); ?>"
                       placeholder="Payment Date" title="Enter the date of the payment"/>

            
            <div class="text">Item: </div>
                <select name="itemno" required>
                    <option value="">SELECT ITEM</option>
                    <?php 
                        $statement = $connection->prepare("SELECT itemNo, itemName FROM item WHERE sellerID = ?");
                        $statement->bind_param("s", $_POST['sellerid']);
                        $statement->execute();
                        $statement->bind_result($ino, $iname);
                        while ($statement->fetch()) {
                            $s = ($itemNo == $ino)? "selected": "";
                            echo "<option value='$ino' $s>$iname</option>";
                        }
                    ?>

                </select>

            <input type="submit" name="confirm" value="Confirm" class="button smoothrectangle"/>
            <input type="submit" name="cancel" value="Cancel" formnovalidate class="button smoothrectangle"/>
    </form>
</div>
        <?php }
 include 'includes/footer.php';
        ?>