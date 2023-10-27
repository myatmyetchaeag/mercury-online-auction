<?php
    $pageTitle = "Receivable Report";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    $statement = $connection->prepare('SELECT b.itemNo, i.itemName, (b.biddingAmount - b.itemPercent) as RAmount, b.BuyerID, bu.BuyerName
                            FROM item i, buyerbidding b, buyer bu
                            WHERE i.itemno = b.itemno AND b.buyerID = bu.buyerID
                            AND i.sellerID = ? AND b.winning = 1 AND b.paymentStatus = 0;');
    $statement->bind_param("s", $_SESSION['sid']);
    $statement->execute();
    $statement->bind_result($itemNo, $itemName, $receivableAmount, $buyerID, $buyerName);
    $statement->store_result();
    if(isset($_SESSION['sid'])):
    include 'includes/s_activities.html';
?>
<?php if($statement->num_rows > 0):?>
        <table class="table">
            <tr class="tableHead">
                <th>No.</th>
                <th>Buyer ID</th>
                <th>Buyer Name</th>
                <th>Item Name</th>
                <th>Receivable Amount</th>
            </tr>
<?php 
        $no = 1;
        while($statement->fetch()){
            ?>
            <tr>
                <td><?php htmlout($no); ?></td>
                <td><?php htmlout($buyerID); ?></td>
                <td><?php htmlout($buyerName); ?></td>
                <td><?php htmlout($itemName); ?></td>
                <td>$<?php htmlout($receivableAmount); ?></td>
            </tr>
            <?php
            $no++;
        }
        ?>
</table>
<?php
    else:
        echo '<p style="text-align: center;">There is no receivable payment for you at the moment.</p>';
    endif;
else:
    echo '<p style="text-align: center;">You must login as seller first if you want to view this page.</p>';
endif;
    include 'includes/footer.php'; ?>