<?php
    $pageTitle = "Approve Items";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $itemStage = "";
    $approvedDate = "";
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    if(isset($_POST['approve'])){
        $itemStage = "Approved";
        $approvedDate = date("Y-m-d");
        $error = "";
        $statement = $connection->prepare("UPDATE item SET itemStage = ?, approvedDate = ? WHERE itemNo = ?");
        $statement->bind_param("ssi", $itemStage, $approvedDate, $_POST['id']);
        $statement->execute();
        $error = $statement->error;
        
        if(empty($error)){
                echo "<script>alert('Item Approved!!!');</script>";
        }else{
                echo "<script>alert('$error');</script>";
        }
        $statement->close();
    }
    
    try {
        $r = $connection->query("SELECT i.sellerID, i.itemNo, i.itemName, i.itemDescription, i.price, i.itemstage, i.image, c.categoryID, c.categoryName
                 FROM item i, category c WHERE i.categoryID = c.categoryID ORDER BY itemStage = 'Registered' DESC");
    } catch (Exception $e) {
        echo 'Error fetching item information from the database!';
    }
    
    foreach ($r as $row) {
        $items[] = array('sellerID' => $row['sellerID'], 
            'itemNo' => $row['itemNo'], 
            'itemName' => $row['itemName'], 
            'itemDescription' => $row['itemDescription'], 
            'price' => $row['price'], 
            'itemStage' => $row['itemstage'], 
            'image' => $row['image'], 
            'categoryID' => $row['categoryID'], 
            'categoryName' => $row['categoryName']);
    }


    if(isset($_SESSION['aid'])):
    include 'includes/a_activities.html';
?>

<?php if(isset($items)): ?>
    
<table class="table">
    <tr class="tableHead">
            <th>No.</th>
            <th>Seller ID</th>
            <th>Item Name</th>
            <th>Item Description</th>
            <th>Price</th>
            <th>Stage</th>
            <th>Image</th>
            <th>Category</th>
            <th>Action</th>
    </tr>
    <?php

        $no = 1;
        foreach($items as $item):
             
    ?>
    
    <tr>
        <td><?php htmlout($no); ?></td>
        <td><?php htmlout($item['sellerID']); ?></td>
        <td><?php htmlout($item['itemName']); ?></td>
        <td><?php htmlout($item['itemDescription']); ?></td>
        <td>$<?php htmlout($item['price']); ?></td>
        <td><strong><?php htmlout($item['itemStage']); ?></strong></td>
        <td><a href="images/items/<?php htmlout($item['image']); ?>"> <img src="images/items/<?php htmlout($item['image']); ?>" target="_brand" style="width: 80px;"/></a></td>
        <td><?php htmlout($item['categoryName']); ?></td>
        <td>
        <form action="" method="post">
            <?php 
            if($item['itemStage'] == 'Registered'){?>
                <div>
                <input type="hidden" name="id" value="<?php
                    htmlout($item['itemNo']); ?>">
                <input type="submit" name="approve" value="Approve" class="button">

            </div>
            <?php

            }else{
                echo 'No Action';
            }?>

        </form>
        </td>
            
    </tr>
    <?php
            $no++;
        endforeach;  

    ?>
</table>
<?php endif;
else:
    echo '<p style="text-align: center;">You must login as the auctioneer first if you want to view this page.</p>';
endif;

include 'includes/footer.php'; ?>