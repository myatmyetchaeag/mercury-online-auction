<?php
    $pageTitle = "Current Bid Active Items";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    if(isset($_SESSION['aid'])):
        
    include 'includes/a_activities.html';
?>    


<table class="table">
        
        <tr class="tableHead">
            <th>No.</th>
            <th>Item Name</th>
            <th>No. of bids</th>
            <th>Max bidding</th>
        </tr>    
        <?php
            $result = $connection->query("SELECT itemNo FROM item WHERE itemStage = 'On Bidding';");
            for ($itemNo = array (); $row = $result->fetch_assoc(); $itemNo[] = $row['itemNo']);
            
            $arraylength = count($itemNo);
            $no = 1;
            for ($index = 0; $index < $arraylength; $index++) {
                $statement = $connection->prepare("SELECT i.itemName, count(b.buyerid) AS noofbid, max(b.biddingAmount) AS maxbidding
                                FROM buyerbidding b, item i
                                WHERE b.itemNo = ? AND i.itemNo = b.itemNo");
                $statement->bind_param("i", $itemNo[$index]);
                $statement->execute();
                $statement->bind_result($itemName, $noofbid, $maxbid);

                while($statement->fetch()){
                    
        ?>
        <tr>
            <td><?php htmlout($no); ?></td>
            <td><?php htmlout($itemName); ?></td>
            <td><?php htmlout($noofbid); ?></td>
            <td>$<?php htmlout($maxbid); ?></td>
        </tr>
        <?php
                    $no++;
                }
                $statement->close();
            }
            
            
        ?>
    </table>

<?php 
    else:
        echo '<p style="text-align: center;">You must login as the auctioneer first if you want to view this page.</p>';
    endif;
include 'includes/footer.php';?>