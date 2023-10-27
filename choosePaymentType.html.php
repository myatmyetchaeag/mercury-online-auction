<?php 
    $pageTitle = "Choose Payment Type";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    $paymentAmount = '';
    $paymentDate = '';
    $paymentType = '';
    $sellerID = '';
    $AucID = '';
    
    if(isset($_POST['confirm'])){
        
        $paymentAmount = $_POST['paymentamount'];
        $paymentDate = $_POST['paymentdate'];
        $paymentType = $_POST['paymenttype'];
        $sellerID = $_POST['sellerid'];
        $AucID = $_SESSION['aid'];
        $statement = $connection->prepare('INSERT INTO payment(`paymentAmount`, `paymentDate`, `paymentType`, `AucID`, `sellerID`) '
                . 'VALUES(?, ?, ?, ?, ?)');
        $statement->bind_param("dssis", $paymentAmount, $paymentDate, $paymentType, $AucID, $sellerID);
        $statement->execute();
        $paymentID = $statement->insert_id;
        if ($statement->error) {
            echo "<script>alert('CONFRIMATION ERROR.');</script>";
        }else{
            $categoryID = "";
            echo "<script>alert('PAYMENT CONFIRMED'); location.assign('report.html.php');</script>";
        }
        $statement->close();
        

    }
    
    if(isset($_SESSION['aid'])):
    include 'includes/a_activities.html';
    
?>

<div class="user-form">
    <form action="confirmPayment.html.php" method="POST" name="form">
        <div class="text">PAYMENT TYPE: </div>
        <select name="paymenttype" required>
            <option value="">SELECT PAYMENT TYPE</option>
            <option value="Registration Fee">Registration Fee</option>
            <option value="Item Percentage">Item Percentage</option>
        </select>
        <div class="text">SELLER NAME: </div>
                <select name="sellerid" required>
                    <option value="">SELECT SELLER</option>
                    <?php 
                        $statement = $connection->prepare("SELECT sellerID, sellerName FROM seller");
                        $statement->execute();
                        $statement->bind_result($sid, $sname);
                        while ($statement->fetch()) {
                            $s = ($sellerID == $sid)? "selected": "";
                            echo "<option value='$sid' $s>$sname</option>";
                        }
                    ?>

                </select>
        
        <input type="submit" name="con" value="Confirm Payment" class="button smoothrectangle">
        
    </form>
</div>
<?php else:
    echo '<p style="text-align: center;">You must login as the auctioneer first if you want to view this page.</p>';
endif;
include 'includes/footer.php';?>