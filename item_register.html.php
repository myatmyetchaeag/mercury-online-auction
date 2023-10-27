<?php
        $pageTitle = "Item Registration";
        include 'includes/header.php';
        
	require "includes/db.inc.php";
        
	$itemName="";
        $itemDescription="";
	$price="";
        $bidIncrement = "";
        $quantity="";
        $itemStage="";
        $validUntil ="";
	$image="";
        $createdDate="";
        $sellerID = "";
        $AucID = "";
	$categoryID="";
        
	$connection = new mysqli($host, $db_user, $db_password, $database);
        
	if (isset($_POST['register'])) {
            $itemName= $_POST['itemname'];
            $itemDescription = $_POST['itemdescription'];
            $price= $_POST['price'];
            $bidIncrement = $_POST['bidIncrement'];
            $quantity = $_POST['quantity'];
            $itemStage = "Registered";
            $validUntil = $_POST['validUntil'];
            $image= $_FILES["image"]["name"];
            $createdDate = date("Y-m-d");
            $sellerID = $_SESSION['sid'];
            $AucID = 1;
            $categoryID = $_POST['categoryid'];

            $statement = $connection->prepare("INSERT INTO item(itemName, itemDescription, price, bidIncrement, quantity, itemstage, validUntil, image, createdDate, sellerID, AucID, categoryID) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param("ssdiisssssii", $itemName, $itemDescription, $price, $bidIncrement, $quantity, $itemStage, $validUntil, $image, $createdDate, $sellerID, $AucID, $categoryID);
            $statement->execute();
            $itemNo = $statement->insert_id;
            $statement->close();

            $imagename = $itemNo.".".pathinfo($image, PATHINFO_EXTENSION);
            $statement = $connection->prepare("UPDATE item SET image = ? WHERE itemNo = ?");
            $statement->bind_param("si", $imagename, $itemNo);
            $statement->execute();
            
            move_uploaded_file($_FILES["image"]["tmp_name"], $imagepath.$imagename);
            
            if ($statement->error) {
                echo "<script>alert('REGISTRATION ERROR.');</script>";
            }else{
                $itemName="";
                $itemDescription="";
                $price="";
                $bidIncrement = "";
                $quantity="";
                $registrationFee="";
                $itemStage="";
                $validUntil ="";
                $image="";
                $createdDate = "";
                $sellerID = "";
                $categoryID="";
                
                echo "<script>alert('ITEM REGISTERED'); location.assign('item_list.html.php');</script>";
            }
            
            $statement->close();        
	}
        
        if(isset($_POST['cancel'])){
            echo "<script>alert('Canceled!!'); location.assign('item_list.html.php');</script>";
        }
        
    if(isset($_SESSION['sid'])):    

?>
    
    <div class="user-form" style="width: 750px;">
        <form action="" method="post" enctype="multipart/form-data">
            <div style="width: 45%; float: left;">
            <div class="text">ITEM NAME:</div>
            <input type="text" name="itemname" maxlength="30" value="<?php htmlout($itemName); ?>" autofocus pattern="[A-Z][a-zA-Z]+" required 
            placeholder="Enter item name" title="Enter item name only in letters."/>

            <div class="text">ITEM DESCRIPTION:</div>
            <textarea rows="4" name="itemdescription" maxlength="100" value="<?php htmlout($itemDescription); ?>" pattern="[A-Z][a-zA-Z0-9]+" required 
            placeholder="Enter item description" title="Enter the description of your item"></textarea>

            <div class="text">PRICE: </div>
            <input type="text" name="price" maxlength="10" required value="<?php htmlout($price); ?>" pattern="[0-9]*[.]?[0-9]+"
            placeholder="Enter item Price" title="Enter price within 10 decimal numbers."/>
            
            <div class="text">BID INCREMENT: </div>
            <input type="text" name="bidIncrement" maxlength="3" required value="<?php htmlout($bidIncrement); ?>" pattern="[0-9]+"
            placeholder="Bidding Increment" title="Bidding Increment"/>


            <div class="text">QUANTITY: </div>
            <input type="text" name="quantity" required maxlength="11" value="<?php htmlout($quantity); ?>"
            pattern="[0-9]+" placeholder="Enter the amount of the item" title="Enter the quantity only in integers."/>
            </div>
            
            <div style="width: 45%; float: right;">
            <div class="text">VALID UNTIL: </div>

            <input type="text" data-format="yyyy-MM-dd" name="validUntil" id="validUntil" maxlength="11" value="<?php htmlout($validUntil); ?>"
                   placeholder="yyyy-mm-dd" title="Enter the end date of the item"/>
            <script type="text/javascript">
            $(document).ready(function () {

                $('#validUntil').datepicker({
                    format: "yyyy-mm-dd",
                    startDate: new Date()+7
                });  

            });
            </script>
            
            
            <div class="text">IMAGE: </div>
            <input type="file" name="image" required title="Choose Image." 
            onchange="showImage(this);"/>
            <img src="" id="image" >
            


            <div class="text">CATEGORY: </div>
            <select name="categoryid" required>
                <option value="">Select Category</option>
                <?php 
                    $statement = $connection->prepare("SELECT * FROM category");
                    $statement->execute();
                    $statement->bind_result($ctid, $ctname);
                    while ($statement->fetch()) {
                        $s = ($categoryID == $ctid)? "selected": "";
                        echo "<option value='$ctid' $s>$ctname</option>";
                    }
                ?>
                
            </select>
            </div>

            <input type="submit" name="register" value="Register" class="button smoothrectangle"/>
            <input type="submit" name="cancel" value="Cancel" formnovalidate  class="button smoothrectangle"/>


        </form>
        
    </div>

    <?php 
    else:
        echo '<p style="text-align: center;">You must login as seller first if you want to view this page.</p>';
    endif;
    
    include 'includes/footer.php'; ?>
