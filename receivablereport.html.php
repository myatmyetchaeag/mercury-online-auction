<?php
    $pageTitle = "Receievable Report";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    include 'includes/a_activities.html';
?>

<form method="POST">
    <div style="margin: 0 0 20px 350px;">
        <input type="submit" name="regfee" value="Registration Fee" class="whitebutton smallrectangle"/>
        <input type="submit" name="itper" value="Item Percentage" class="whitebutton smallrectangle"/>
    </div>
</form>
    <?php if(isset($_POST['regfee'])):?>
    <div class="form-header">
        <h2>Registration Fee</h2>
        <div class="strip"></div>
    </div>
        <table class="table">
            <tr class="tableHead">
                <th>No.</th>
                <th>Seller ID.</th>
                <th>Seller Name</th>
                <th>Registration Fee</th>
                <th>Registered Date</th>
                <th>Due Date</th>
                <th>Payment Type</th>
            </tr>
<?php
        $paymentType = 'Registration Fee';
        $statement = $connection->prepare('SELECT sellerName, registrationFee, registeredDate, regPayDueDate, sellerID
                       FROM seller WHERE regFeePaid = 0');
        $statement->execute();
        $statement->bind_result($sellerName, $registrationFee, $registeredDate, $regPayDueDate, $sellerID);
        $no = 1;
        while($statement->fetch()){
            ?>
            <tr>
                <td><?php htmlout($no); ?></td>
                <td><?php htmlout($sellerID); ?></td>
                <td><?php htmlout($sellerName); ?></td>
                <td>$<?php htmlout($registrationFee); ?></td>
                <td><?php htmlout($registeredDate); ?></td>
                <td><?php htmlout($regPayDueDate); ?></td>
                <td><?php htmlout($paymentType); ?></td>
            </tr>
            <?php
            $no++;
        }
        
        ?>
    
    </table>
<?php elseif(isset($_POST['itper'])):?>
    <div class="form-header">
        <h2>Item Percentage</h2>
    </div>
    <table class="table">
            <tr class="tableHead">
                <th>No.</th>
                <th>Seller ID.</th>
                <th>Seller Name</th>
                <th>Item No</th>
                <th>Item Name</th>
                <th>Commission</th>
                <th>Payment Type</th>
                <th>Winner</th>
            </tr>
<?php
        $paymentType = 'Item Percentage';
        $statement = $connection->prepare('SELECT b.itemNo, i.itemName, b.itemPercent, s.sellerID, s.sellerName, b.buyerID
                            FROM item i, buyerbidding b, seller s
                            WHERE i.itemno = b.itemno
                            AND s.sellerID = i.sellerID
                            AND b.winning = 1 AND b.commissionPaid = 0');
        $statement->execute();
        $statement->bind_result($itemNo, $itemName, $itemPercent, $sellerID, $sellerName, $buyerID);
        $no = 1;
        while($statement->fetch()){
            ?>
            <tr>
                <td><?php htmlout($no); ?></td>
                <td><?php htmlout($sellerID); ?></td>
                <td><?php htmlout($sellerName); ?></td>
                <td><?php htmlout($itemNo); ?></td>
                <td><?php htmlout($itemName); ?></td>
                <td>$<?php htmlout($itemPercent); ?></td>
                <td><?php htmlout($paymentType); ?></td>
                <td><?php htmlout($buyerID); ?></td>
            </tr>
            <?php
            $no++;
        }
        
        ?>
    
    </table>
<?php endif;?>

<?php
    include 'includes/footer.php';
?>