<?php
    $pageTitle = "Confirmation";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    if(isset($_POST['cancel'])){
        if(isset($_GET['cid'])){
            echo "<script>alert('Canceled!!'); location.assign('category_list.html.php');</script>";
            
        }elseif(isset($_GET['ino'])){
            echo "<script>alert('Canceled!!'); location.assign('item_list.html.php');</script>";
        }
        
    }
    
    // Delete Category
    if(isset($_GET['cid']) && isset($_POST['delete'])){
        $statement = $connection->prepare("SELECT count(itemNo) FROM item WHERE categoryID = ?");
        $statement->bind_param("i", $_GET['cid']);
        $statement->execute();
        $statement->bind_result($noofItems);
        $statement->fetch();
        $statement->close();

        if($noofItems == 0){
            $statement = $connection->prepare("DELETE FROM category where categoryID = ?");
            $statement->bind_param("i", $_GET["cid"]);
            $statement->execute();
            $statement->close();
            echo "<script>alert('Category Deleted!!'); location.assign('category_list.html.php');</script>";
        }else{
            echo "<script>alert('This category has been selected by an item. It cannot be deleted!!!'); "
            . "location.assign('category_list.html.php');</script>";
        }
    }
    
    if(isset($_GET['ino']) && isset($_POST['delete'])){
        $itemNo = $_GET['ino'];
        $statement = $connection->prepare("SELECT image, itemStage FROM item WHERE itemNo = ?");
        $statement->bind_param("i", $itemNo);
        $statement->execute();
        $statement->bind_result($image, $itemStage);
        $statement->fetch();
        $statement->close();
        
        $statement = $connection->prepare("SELECT imageName FROM itemattachment where itemNo = ?;");
        $statement->bind_param("i", $itemNo);
        $statement->execute();
        $statement->bind_result($imageName);
        $statement->fetch();
        $statement->close();
        
        
        
        if($itemStage == 'On Bidding'){
             echo "<script>alert('This item is on bidding. It cannot be deleted!!!'); "
            . "location.assign('item_list.html.php');</script>";
        }else{
            $result = $connection->query("SELECT imageName FROM itemattachment where itemNo = $itemNo;");
            for ($imageName = array (); $row = $result->fetch_assoc(); $imageName[] = $row['imageName']);
            $arraylength = count($imageName);
            for ($index = 0; $index < $arraylength; $index++) {
                echo $imageName[$index];
                unlink($imagepath.$imageName[$index]);
            }
            
            unlink($imagepath.$image);
            
            $statement = $connection->prepare("DELETE FROM itemAttachment where itemNo = ?");
            $statement->bind_param("i", $itemNo);
            $statement->execute();
            $statement->close();
            
            $statement = $connection->prepare("DELETE FROM item where itemNo = ?");
            $statement->bind_param("i", $itemNo);
            $statement->execute();
            $statement->close();

            echo "<script>alert('Item Deleted'); location.assign('item_list.html.php');</script>";
        }
        
        
          
    }
    
    // Delete item image
    if(isset($_GET['imageID']) && isset($_POST['delete'])){
        $statement = $connection->prepare("SELECT imageName from itemattachment WHERE imageID = ?");
        $statement->bind_param("i", $_GET['imageID']);
        $statement->execute();
        $statement->bind_result($imageName);
        $statement->fetch();
        $statement->close();
        
        unlink($imagepath.$imageName);

        $statement = $connection->prepare("DELETE FROM itemattachment where imageID = ?");
        $statement->bind_param("i", $_GET['imageID']);
        $statement->execute();
        $statement->close();
        
        echo "<script>alert('Image Deleted'); location.assign('item_list.html.php');</script>";
          
    }
    
    
    
?>

    <div class="user-form">
        <form method="post" action="">
            <div class="text">ARE YOU SURE YOU WANT TO DELETE THIS RECORD???</div>
            
            <input type="submit" name="delete" value="YES" class="button" />
            <input type="submit" name="cancel" value="NO" class="button" />

        </form>
    </div>
    

    <?php include 'includes/footer.php'; ?>
    	
