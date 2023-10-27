<?php
    $pageTitle = "Register your Account";
    include 'includes/header.php';
    
    
    require "includes/db.inc.php";
    
    $buyerName = "";
    $buyerEmail = "";
    $password = "";
    $registeredDate = "";
    $address = "";
    $phoneNo = "";
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    if(isset($_POST['register'])){
        $buyerName = $_POST['buyername'];
        $buyerEmail = $_POST['buyeremail'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $registeredDate = date('Y-m-d');
        $address = $_POST['address'];
        $phoneNo = $_POST['phoneno'];
        
       
        
        $statement = $connection->prepare("SELECT * FROM buyer WHERE buyerEmail= ?");
        $statement->bind_param("s", $buyerEmail);
        $statement->execute();

        if ($statement->fetch()) {
            echo "<script>alert('Email already exist.');</script>";
        }else{
            $statement->close();

            $id = "B0001";
            $statement = $connection->prepare("SELECT MAX(buyerID) FROM buyer");
            $statement->execute();
            $statement->bind_result($buyerID);
            if ($statement->fetch()) {
                $bid = substr($buyerID, 1) + 1;
                $id = "B".str_pad($bid, 4, "0", STR_PAD_LEFT);
            }
            $statement->close();

            $statement = $connection->prepare("INSERT INTO buyer(buyerID, buyerName, buyerEmail, password, registeredDate, address, phoneNo) VALUES(?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param("ssssssi", $id, $buyerName, $buyerEmail, $password, $registeredDate, $address, $phoneNo);
            $statement->execute();
            if ($statement->error) {
                echo "<script>alert('REGISTRATION ERROR.');</script>";
            }else{
                $buyerName="";
                $buyerEmail="";
                $password ="";
                $registeredDate = "";
                $address="";
                $phoneNo="";
                echo "<script>alert('REGISTRATION COMPLETED!'); location.assign('index.php');</script>";
            }
            $statement->close();

        }
    }
?>
    
        <div class="user-form">
            <form action="" method="POST" name="form">
                <div class="text">BUYER NAME:</div>
                <input type="text" name="buyername" value="<?php htmlout($buyerName); ?>" maxlength="50" required pattern="[A-Z][a-zA-Z ]+" 
                       placeholder="Enter your name" autofocus title="Enter your name only in letters"/>

                <div class="text">BUYER EMAIL:</div>
                <input type="email" name="buyeremail" value="<?php htmlout($buyerEmail); ?>" maxlength="50" required
                       placeholder="Enter your email" title="Enter your email in required format"/>

                <div class="text">PASSWORD:</div>
                <input type="password" name="password" maxlength="100" required
                       placeholder="Enter your password" 
                       onchange="document.getElementById('cpassword').pattern = this.value"/>
                <!--frm.cpassword.pattern = this.value;-->

                <div class="text">CONFIRM PASSWORD:</div>
                <input type="password" name="cpassword" id="cpassword"
                       maxlength="100" required
                       placeholder="Confirm your password"
                       title="Your confirm password must match your password" />
                

                <div class="text">ADDRESS:</div>
                <textarea rows="3" cols="20" name="address"  maxlength="100" 
                          pattern="[A-Z0-9][a-zA-Z0-9\-\(\)., ]+"
                          placeholder="Enter your address" required><?php htmlout($address); ?></textarea>


                <div class="text">PHONE NUMBER:</div>
                <input type="text" name="phoneno" value="<?php htmlout($phoneNo); ?>" maxlength="15" required 
                       pattern="[0-9]+" placeholder="Enter your phone number"
                       title="Enter your phone number in digit only" />

                <input type="submit" name="register" value="Register" class="button smoothrectangle"/>
                <input type="submit" name="cancel" value="Cancel" formnovalidate class="button smoothrectangle"/>
            </form>
        </div>
    
    <?php include 'includes/footer.php'; ?>
