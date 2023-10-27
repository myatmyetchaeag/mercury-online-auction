<?php
    $pageTitle = "My Profile";
    include 'includes/header.php';
    
    
    require "includes/db.inc.php";
    
    $newpass = "";
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    /* CHANGE BUYER PASSWORD */
    if(isset($_POST['update']) && isset($_SESSION['bid'])){
        
        $newpass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
        $error = "";
        $statement = $connection->prepare("UPDATE buyer SET password = ?
                WHERE buyerID = ?");
        $statement->bind_param("ss", $newpass, $_SESSION['bid']);
        $statement->execute();
        $error = $statement->error;
        $statement->close();
        if(empty($error)){
            echo "<script>alert('Your password has been changed!!!'); location.assign('buyer_profile.html.php');</script>";
        }else{
            echo "<script>alert('Unable to change your password!! $error');</script>";
        }   
    }
    
    
    /* CHANGE SELLER PASSWORD */
    if(isset($_POST['update']) && isset($_SESSION['sid'])){
        
        $newpass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
        
        try {
            $statement = $connection->prepare("UPDATE seller SET password = ?
                WHERE sellerID = ?");
            $statement->bind_param("ss", $newpass, $_SESSION['sid']);
            $statement->execute();
            echo "<script>alert('Your Password has been changed!!!'); location.assign('seller_profile.html.php');</script>";
        } catch (Exception $e) {
            $error = $statement->error;
            echo "<script>alert('Unable to change your password!! $error');</script>";
        }
        $statement->close();
        
    }
        
    if(isset($_POST['cancel']) && isset($_SESSION['bid'])){
        echo "<script>location.assign('buyer_profile.html.php');</script>";
    }
    
    if(isset($_POST['cancel']) && isset($_SESSION['sid'])){
        echo "<script>location.assign('seller_profile.html.php');</script>";
    }
    
?>
    
        <div class="user-form">
            <form action="" method="POST" name="form">
                
<!--                <div class="text">OLD PASSWORD: </div>
                <input type="password" name="oldpass" value="<?php htmlout($oldpass); ?>" maxlength="8" required readonly=""/>-->

                <div class="text">NEW PASSWORD:</div>
                <input type="password" name="newpass" maxlength="100" required
                       placeholder="Enter your password" 
                       onchange="document.getElementById('cpassword').pattern = this.value"/>
                

                <div class="text">NEW PASSWORD (CONFIRM): </div>
                <input type="password" name="cpassword" id="cpassword"
                       maxlength="100" required
                       placeholder="Confirm your password"
                       title="Your confirm password must match your password" />

                <input type="submit" name="update" value="Save Changes" class="button smoothrectangle"/>
                <input type="submit" name="cancel" value="Cancel" formnovalidate class="button smoothrectangle"/>
                
            </form>
        </div>
    
    <?php include 'includes/footer.php'; ?>
