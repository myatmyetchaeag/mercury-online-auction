<?php
    $pageTitle = "My Profile";
    include 'includes/header.php';
    
    
    require "includes/db.inc.php";
    
    $sellerName = "";
    $sellerEmail = "";
    $password = "";
    $address = "";
    $phoneNo = "";
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    if(isset($_POST['update'])){
        $sellerName = $_POST['sellername'];
        $sellerEmail = $_POST['selleremail'];
        //$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $address = $_POST['address'];
        $phoneNo = $_POST['phoneno'];
        
        $error = "";
        
        $statement = $connection->prepare("UPDATE seller SET sellerName = ?, address = ?, phoneno = ?
                WHERE sellerID = ?");
        $statement->bind_param("ssis", $sellerName, $address, $phoneNo, $_SESSION['sid']);
        $statement->execute();
        $error = $statement->error;
        $statement->close();
            
                
            
        if(empty($error)){
            echo "<script>alert('Updating Process is Completed!!!');</script>";
        }else{
            echo "<script>alert('Unable to complete the process!! $error');</script>";
        }
        
        
    }
    
    if(isset($_SESSION['sid'])){
            $statement = $connection->prepare("SELECT sellerName, sellerEmail, address, phoneno FROM seller WHERE sellerID = ?");
            $statement->bind_param("s", $_SESSION['sid']);
            $statement->execute();
            $statement->bind_result($sellerName, $sellerEmail, $address, $phoneNo);
            $statement->fetch();
            $statement->close();
        }
        
    if(isset($_POST['changepass'])){
        header("Location: change_password.html.php");
    }
    if(isset($_SESSION['message'])){
        $message = $_SESSION['message'];
        echo "<div style = 'width: 50%; margin: auto; margin-bottom: 10px;' class='warningalert'><span>$message</span></div>";
    }
    
    if(isset($_SESSION['sid'])):
        
    include 'includes/s_activities.html';
?>

        <div class="user-form">
            <form action="" method="POST" name="form">
                
                <div class="text">SELLER NAME:</div>
                <input type="text" name="sellername" value="<?php echo $sellerName; ?>" maxlength="50" required pattern="[A-Z][a-zA-Z ]+" 
                       placeholder="Enter your name" autofocus title="Enter your name only in letters"/>

                <div class="text">SELLER EMAIL:</div>
                <input type="email" name="selleremail" value="<?php htmlout($sellerEmail); ?>" maxlength="50" required
                       placeholder="Enter your email" title="Your email cannot be changed" readonly=""/>

                <div class="text">ADDRESS:</div>
                <textarea rows="3" cols="20" name="address"  maxlength="100" 
                          pattern="[A-Z0-9][a-zA-Z0-9\-\(\)., ]+"
                          placeholder="Enter your address" required><?php htmlout($address); ?></textarea>


                <div class="text">PHONE NUMBER:</div>
                <input type="text" name="phoneno" value="<?php htmlout($phoneNo); ?>" maxlength="15" required 
                       pattern="[0-9]+" placeholder="Enter your phone number"
                       title="Enter your phone number in digit only" />

                <input type="submit" name="update" value="Save Changes" class="button smoothrectangle"/>
                <input type="submit" name="cancel" value="Cancel" formnovalidate class="button smoothrectangle"/>
                <input type="submit" name="changepass" value="Change Password" class="button smoothrectangle"  style="margin-top: 10px;"/>
            </form>
        </div>
    
    <?php 
    else:
        echo '<p style="text-align: center;">You must login as seller first if you want to view this page.</p>';
    endif;
    include 'includes/footer.php'; ?>
