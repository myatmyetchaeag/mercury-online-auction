<?php
    $pageTitle = "Seller Registration";
    include 'includes/header.php';
    
    require "includes/db.inc.php";
    
    $sellerName = "";
    $sellerEmail = "";
    $password = "";
    $registeredDate = "";
    $registrationFee = "";
    $regPayDueDate = "";
    $address = "";
    $phoneNo = "";
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    if(isset($_POST['register'])){
        $sellerName = $_POST['sellername'];
        $sellerEmail = $_POST['selleremail'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $registeredDate = date('Y-m-d');
        $registrationFee = 8; 
        $regPayDueDate = date('Y-m-d', strtotime("+ 1 month"));
        $address = $_POST['address'];
        $phoneNo = $_POST['phoneno'];
        
       
        
        $statement = $connection->prepare("SELECT * FROM seller WHERE sellerEmail= ?");
        $statement->bind_param("s", $sellerEmail);
        $statement->execute();

        if ($statement->fetch()) {
            echo "<script>alert('Email already exist.');</script>";
        }else{
            $statement->close();

            $id = "S0001";
            $statement = $connection->prepare("SELECT MAX(sellerID) FROM seller");
            $statement->execute();
            $statement->bind_result($sellerID);
            if ($statement->fetch()) {
                $sid = substr($sellerID, 1) + 1;
                $id = "S".str_pad($sid, 4, "0", STR_PAD_LEFT);
            }
            $statement->close();

            $statement = $connection->prepare("INSERT INTO seller(sellerID, sellerName, sellerEmail, password, registeredDate, registrationFee, regPayDueDate, address, phoneNo) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->bind_param("sssssissi", $id, $sellerName, $sellerEmail, $password, $registeredDate, $registrationFee, $regPayDueDate, $address, $phoneNo);
            $statement->execute();
            if ($statement->error) {
                echo "<script>alert('REGISTRATION ERROR.');</script>";
            }else{
                $sellerName = "";
                $sellerEmail = "";
                $password = "";
                $registeredDate = "";
                $registrationFee = "";
                $address = "";
                $phoneNo = "";
                echo "<script>alert('REGISTRATION COMPLETED!'); location.assign('index.php');</script>";
            }
            $statement->close();

        }
    }
?>

    <div class="user-form">
        <form action="" method="POST" name="form">
            <div class="text">SELLER NAME:</div>
            <input type="text" name="sellername" value="<?php htmlout($sellerName); ?>" maxlength="50" required pattern="[A-Z][a-zA-Z ]+" 
                   placeholder="Enter your name" autofocus title="Enter your name only in letters"/>

            <div class="text">SELLER EMAIL:</div>
            <input type="email" name="selleremail" value="<?php htmlout($sellerEmail); ?>" maxlength="50" required
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
            <textarea rows="3" cols="20" name="address" maxlength="100" 
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

    <?php include 'includes/footer.php';?>