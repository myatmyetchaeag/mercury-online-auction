<?php
    $pageTitle = "My Profile";
    include 'includes/header.php';
    
    
    require "includes/db.inc.php";
    
    $buyerName = "";
    $buyerEmail = "";
    $password = "";
    $address = "";
    $phoneNo = "";
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    if(isset($_POST['update'])){
        $buyerName = $_POST['buyername'];
        $buyerEmail = $_POST['buyeremail'];
        $address = $_POST['address'];
        $phoneNo = $_POST['phoneno'];
        
        $error = "";
        $statement = $connection->prepare("UPDATE buyer SET buyerName = ?, address = ?, phoneno = ?
                WHERE buyerID = ?");
        $statement->bind_param("ssis", $buyerName, $address, $phoneNo, $_SESSION['bid']);
        $statement->execute();
        $error = $statement->error;
        $statement->close();
            
        if(empty($error)){
            echo "<script>alert('Updating Process is Completed!!!');</script>";
        }else{
            echo "<script>alert('Unable to complete the process!! $error');</script>";
        }
        
        
    }
    
    if(isset($_SESSION['bid'])){
        $statement = $connection->prepare("SELECT buyerName, buyerEmail, address, phoneno FROM buyer WHERE buyerID = ?");
        $statement->bind_param("s", $_SESSION['bid']);
        $statement->execute();
        $statement->bind_result($buyerName, $buyerEmail, $address, $phoneNo);
        $statement->fetch();
        $statement->close();
    }
        
    if(isset($_POST['changepass'])){
        header("Location: change_password.html.php");
    }
    
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
        <div class="user-form">
            <form action="" method="POST" name="form">
                
                <div class="text">BUYER NAME:</div>
                <input type="text" name="buyername" value="<?php htmlout($buyerName); ?>" maxlength="50" required pattern="[A-Z][a-zA-Z ]+" 
                       placeholder="Enter your name" autofocus title="Enter your name only in letters"/>

                <div class="text">BUYER EMAIL:</div>
                <input type="email" name="buyeremail" value="<?php htmlout($buyerEmail); ?>" maxlength="50" required
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
                <input type="submit" name="changepass" value="Change Password" class="button smoothrectangle" style="margin-top: 10px;"/>
            </form>
        </div>
    
    <?php 
    else:
        echo '<p style="text-align: center;">You must login as buyer first if you want to view this page.</p>';
    endif;
    
    include 'includes/footer.php'; ?>
