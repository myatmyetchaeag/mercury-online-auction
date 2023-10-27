<?php
    $pageTitle = "Outstanding Payment Report";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    
    $statement = $connection->prepare('SELECT b.itemNo, i.itemName, b.biddingAmount
                        FROM item i, buyerbidding b
                        WHERE i.itemNo = b.itemNo
                        AND b.buyerID = ? and b.winning = 1');
    $statement->bind_param("s", $_SESSION['bid']);
    $statement->execute();
    $statement->bind_result($itemNo, $itemName, $paymentAmount);
    
    $statement->store_result();
    
    if(isset($_SESSION['bid'])):
        
?>
<div class="dropdown-wrapper">
    <div id="dd" class="p-dropdown">Activities
        <ul class="dropdown-content">
            <li><a href="buyer_profile.html.php"><span></span>Profile</a></li>
            <li><a href="buyerpaymentreport.html.php"><span></span>Outstanding Payment Report</a></li>
        </ul>
    </div>
</div>
<?php if($statement->num_rows > 0):?>
<table class="table">
                <tr class="tableHead">
                    <th>No.</th>
                    <th>Item No.</th>
                    <th>Item Name</th>
                    <th>Payment Amount</th>
                </tr>
        <?php

        $no = 1;
        
        while($statement->fetch()){
            ?>
            <tr>
                <td><?php htmlout($no); ?></td>
                <td><?php htmlout($itemNo); ?></td>
                <td><?php htmlout($itemName); ?></td>
                <td>$<?php htmlout($paymentAmount); ?></td>
            </tr>
            <?php
            $no++;
        }?>
        
    
    </table>

<?php   

    else:
        echo '<p style="text-align: center;">There is no payment to make at the moment.</p>';
    endif;
else:
    echo '<p style="text-align: center;">You must login as buyer first if you want to view this page.</p>';
endif;    
    
    include 'includes/footer.php';
?>