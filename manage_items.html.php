<?php
    $pageTitle = "Item";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
        $itemName="";
        $itemDescription="";
	$price="";
        $quantity="";
        $validUntil ="";
	$image="";
	$categoryID="";
        
	$connection = new mysqli($host, $db_user, $db_password, $database);
        
	if (isset($_POST['update'])) {
            $itemNo = $_GET['ino'];
            $itemName= $_POST['itemname'];
            $itemDescription = $_POST['itemdescription'];
            $price= $_POST['price'];
            $bidIncrement = $_POST['bidIncrement'];
            $quantity = $_POST['quantity'];
            $validUntil = $_POST['validUntil'];
            $image= $_FILES["image"]["name"];
            $categoryID = $_POST['categoryid'];
            
            $error = "";
            
            if(empty($image)){
                $statement = $connection->prepare("UPDATE item SET itemName = ?, itemDescription = ?, price = ?, bidIncrement = ?, quantity = ?, validUntil = ?, categoryID = ? 
                        WHERE itemNo = ?");
                $statement->bind_param("ssdiissi", $itemName, $itemDescription, $price, $bidIncrement, $quantity, $validUntil, $categoryID, $itemNo);
                $statement->execute();
                $error = $statement->error;
                $statement->close();
            }else{
                $imagename = $itemNo.".".pathinfo($image, PATHINFO_EXTENSION);
                
                $statement = $connection->prepare("SELECT image FROM item WHERE itemNo = ?");
                $statement->bind_param("i", $itemNo);
                $statement->execute();
                $statement->bind_result($image);
                $statement->fetch();
                $statement->close();
        
                unlink($imagepath.$image);
                
                $statement = $connection->prepare("UPDATE item SET itemName = ?, itemDescription = ?, price = ?, quantity = ?, validUntil = ?, image = ? , categoryID = ? 
                      WHERE itemNo = ?");
                $statement->bind_param("ssdisssi", $itemName, $itemDescription, $price, $quantity, $validUntil, $imagename, $categoryID, $itemNo);
                $statement->execute();
                $error = $statement->error;
                $statement->close();
                
                move_uploaded_file($_FILES["image"]["tmp_name"], $imagepath.$imagename);
            }
            
            if(empty($error)){
                echo "<script>alert('UPDATE SUCCESSFUL!!!'); location.assign('');</script>";
            }else{
                echo "<script>alert('$error');</script>";
            }
            
                
	}
        
        $itemStage = "";
        if(isset($_POST['close'])){
            $itemStage = "Closed";

            $statement = $connection->prepare("UPDATE item SET itemStage = ? WHERE itemNo = ?");
            $statement->bind_param("si", $itemStage, $_POST['ino']);
            $statement->execute();
            

            if($statement->error){
                echo "<script>alert('Unable to close lot!');</script>";    
            }else{
                echo "<script>alert('Closed Lot!!!'); location.assign('item_list.html.php');</script>";
            }
            $statement->close();
        }
        
        $winning = '';
        $itemNo = '';
        $itemStage = '';
        $itemPercent = '';
        $commission = '';
        if(isset($_POST['choose'])){
            $winning = 1;
            $itemNo = $_GET['ino'];
            $itemStage = 'Sold';
            $commission = 7.5;
            
            $statement = $connection->prepare("SELECT b.buyerID, b.biddingAmount
                        FROM buyerbidding b
                        WHERE b.itemNo = ?
                        AND biddingAmount IN
                        (SELECT MAX(biddingAmount) FROM buyerbidding WHERE itemNo = b.itemNo);");
            $statement->bind_param("i", $itemNo);
            $statement->execute();
            $statement->bind_result($buyerID, $biddingAmount);
            $statement->fetch();
            $statement->close();
            
            $itemPercent = $biddingAmount * $commission / 100;
            
            $statement = $connection->prepare("UPDATE buyerbidding SET winning = ?, itemPercent = ?, commission = ? "
                    . "WHERE itemNo = ? AND buyerID = ?");
            $statement->bind_param("iddis", $winning, $itemPercent, $commission, $itemNo, $buyerID);
            $statement->execute();
            $bb_error = $statement->error;
            $statement->close();
            
            $statement = $connection->prepare("UPDATE item SET itemStage = ? WHERE itemNo = ?");
            $statement->bind_param("si", $itemStage, $itemNo);
            $statement->execute();
            $i_error = $statement->error;
            $statement->close();
            
            if(empty($bb_error) && empty($i_error)){
                echo "<script>alert('The winner of this bid is chosen.');</script>";
            }else{
                echo "<script>alert($bb_error, $i_error);</script>";
            }
            
               
        }
        
        if(isset($_POST['delete'])){
            header("Location: delete_confirmation.html.php");
        }
        
        if(isset($_GET['ino'])){
            $itemNo = $_GET['ino'];
            $statement = $connection->prepare("SELECT count(buyerID) FROM buyerbidding WHERE itemNo = ?");
            $statement->bind_param("i", $itemNo);
            $statement->execute();
            $statement->bind_result($noofbid);
            $statement->fetch();
            $statement->close();
            
            $statement = $connection->prepare("SELECT itemName, itemDescription, price, bidIncrement, quantity, itemStage, validUntil, image, categoryID FROM item WHERE itemNo = ?");
            $statement->bind_param("i", $itemNo);
            $statement->execute();
            $statement->bind_result($itemName, $itemDescription, $price, $bidIncrement, $quantity, $itemStage, $validUntil, $image, $categoryID);
            $statement->fetch();
            $statement->close();
            
            $statement = $connection->prepare("SELECT max(biddingAmount) FROM buyerbidding WHERE itemNo = ?");
            $statement->bind_param("i", $itemNo);
            $statement->execute();
            $statement->bind_result($biddingAmount);
            $statement->fetch();
            $statement->close();
        }
        
?>
<form action="" method="post" enctype="multipart/form-data">
    <div><a href="item_list.html.php" class="button smoothrectangle" style=""><</a></div>

    <div class="eimage"><span></span>
        
        <a href="images/items/<?php htmlout($image);?>" >
            <img src="images/items/<?php htmlout($image);?>" alt="<?php htmlout($itemName);?>" style="width: 400px; height: auto;" id="image"></a>
                
            <br/>
            <?php
                $statement = $connection->prepare("SELECT imageID, imageName FROM itemattachment WHERE itemNo = ?");
                $statement->bind_param("i", $_GET['ino']);
                $statement->execute();
                $statement->bind_result($imageID, $imageName);
                while($statement->fetch()){
                    ?>
            <a href="images/items/<?php htmlout($imageName);?>" >
                <img src="images/items/<?php htmlout($imageName); ?>" alt="<?php htmlout($imageName); ?>" style="width: 90px; margin-right: 2px; margin-bottom: 10px;">
            </a>
                    <?php
                }
                $statement->close();
            ?>
                    <br/>
                    <a href="upload_image.html.php?ino=<?php htmlout($itemNo); ?>" class="button smoothrectangle" >Upload More Image</a>
        
    </div>
    <div class="desc">
        <table>
            <tr>
                <td><b>Item Name: </b></td>
                <td>
                    <input type="text" name="itemname" maxlength="30" value="<?php htmlout($itemName); ?>" autofocus pattern="[A-Z][a-zA-Z]+" required 
                placeholder="Enter item name" title="Enter item name only in letters."/>
                </td>
            </tr>
            <tr>
                <td><b>Description: </b></td>
                <td>
                    <textarea rows="4" name="itemdescription" maxlength="100" pattern="[A-Z][a-zA-Z0-9]+" required 
                              placeholder="Enter item description" title="Enter the description of your item"><?php htmlout($itemDescription); ?></textarea>
                </td>
            </tr>
            <tr>
                <td><b>Price: </b></td>
                <td>
                    <input type="text" name="price" maxlength="10" required value="<?php htmlout($price); ?>" pattern="[0-9]*[.]?[0-9]+"
                    placeholder="Enter item Price" title="Enter price within 10 decimal numbers."/>
                </td>
            </tr>
            <tr>
                <td><b>Bid Increment: </b></td>
                <td>
                    <input type="text" name="bidIncrement" maxlength="3" required value="<?php htmlout($bidIncrement); ?>" pattern="[0-9]+"
                    placeholder="Bidding Increment" title="Bidding Increment"/>
                </td>
            </tr>
            <tr>
                <td><p><b>Quantity: </b></p></td>
                <td>
                    <input type="text" name="quantity" required maxlength="11" value="<?php htmlout($quantity); ?>"
                           pattern="[0-9]+" placeholder="Enter the amount of the item" title="Enter the quantity only in integers." />
                </td>
            </tr>
            <tr>
                <td><b>Ends: </b></td>
                <td>
                    <input type="text" data-format="yyyy-MM-dd" name="validUntil" id="validUntil" maxlength="11" value="<?php htmlout($validUntil); ?>"
                   placeholder="Valid end date of an item" title="Enter the end date of the item"/>
                    <script type="text/javascript">
                    $(document).ready(function () {

                        $('#validUntil').datepicker({
                            format: "yyyy-mm-dd",
                            startDate: new Date()+7
                        });  

                    });
                    </script>
                </td>
            </tr>
            <tr>
                <td><b>Image: </b></td>
                <td>
                    <input type="file" name="image" title="Choose Image." 
                    onchange="showImage(this);"/>
                </td>
                
                
            </tr>
            <tr>
                <td><b>Category: </b></td>
                <td>
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
                </td>
            </tr>
            
            <tr class="withoutinput">
                <td><b>Item Stage: </b></td>
                <td><?php htmlout($itemStage); ?></td>
            </tr>
            <tr class="withoutinput">
                <td><b>No of Bids on this item: </b></td>
                <td><?php htmlout($noofbid); ?></td>
            </tr>
            <tr class="withoutinput">
                <td><b>Max Bid (Price): </b></td>
                <?php if($biddingAmount == 0){?>
                <td>$<?php htmlout($price); ?></td>
                <?php }else{ ?>
                <td>$<?php htmlout($biddingAmount); ?></td>
                <?php } ?>
                
            </tr>
            <tr class="withoutinput">
                <td colspan="2">
                    
                    <input type="hidden" name="ino" value="<?php htmlout($itemNo); ?>">
                    <input type="submit" value="Update" name="update" class="button smoothrectangle">
                    <a href="delete_confirmation.html.php?ino=<?php htmlout($itemNo); ?>" class="button smoothrectangle">Delete</a>
                    
                    <input type="submit" value="Close lot" name="close" class="button smoothrectangle">
                    <input type="submit" value="Cancel" name="cancel" formnovalidate class="button smoothrectangle"/>
                </td>
            </tr>
        </table>
        
    
    </div>
    <div class="">
        <div class="form-header">
            <h2>Choose Bid Winner</h2>
            <div class="strip"></div>
        </div>
        
        <input type="submit" value="Choose" name="choose" class="button smoothrectangle" style="margin-left: 420px; margin-bottom: 10px;"> 
        <table class="table">
            <tr class="tableHead">
                <th>No.</th>
                <th>Buyer ID</th>
                <th>Bidding Amount</th>
                <th>Bidding Date</th>
            </tr>
            <?php
                $statement->prepare("SELECT buyerID, biddingAmount, biddingDate
                                    FROM buyerbidding
                                    WHERE itemNo = ?
                                    ORDER BY biddingAmount DESC;");
                $statement->bind_param("i", $_GET['ino']);
                $statement->execute();
                $statement->bind_result($buyerID, $biddingAmount, $biddingDate);
                
                $no = 1;
                while($statement->fetch()){
            ?>
            <tr>
                <th><?php htmlout($no);?></th>
                <th><?php htmlout($buyerID);?></th>
                <th><?php htmlout($biddingAmount);?></th>
                <th><?php htmlout($biddingDate);?></th>
            </tr>
            <?php
                    $no++;
                }
            ?>
        </table>
    </div>
</form>

<?php
    include 'includes/footer.php';
?>