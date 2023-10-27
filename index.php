<?php
    $pageTitle = "User Login";
    
    include 'includes/header.php';
    require "includes/db.inc.php";
    
    if (isset($_POST['login'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $connection = new mysqli($host, $db_user, $db_password, $database);
        
        $statement = $connection->prepare("SELECT buyerID, password FROM buyer WHERE buyerEmail = ?");
        $statement->bind_param("s", $email);
        $statement->execute();
        $statement->bind_result($id, $pw);
        if($statement->fetch()){
            if(password_verify($password, $pw)){
                $_SESSION["bid"] = $id; 
                header("Location: browse_items.html.php");
            }else{
                echo "<script>alert('Unable to login. Check your email and password.');</script>";
            }
        }else{
            $statement->close();
            
            $statement = $connection->prepare("SELECT sellerID, password FROM seller WHERE sellerEmail = ?");
            $statement->bind_param("s", $email);
            $statement->execute();
            $statement->bind_result($id, $pw);
            if($statement->fetch()){
                if(password_verify($password, $pw)){
                    $statement->close();
                    $_SESSION['sid'] = $id;
                    $statement = $connection->prepare("SELECT regPayDueDate, day(regPayDueDate) FROM seller WHERE sellerID = ?");
                    $statement->bind_param("s", $_SESSION['sid']);
                    $statement->execute();
                    $statement->bind_result($regPayDueDate, $Dueday);
                    $statement->fetch();
                    $statement->close();
                    $currentDay = date('d');
                    $beforeDue = $currentDay - 7;
                    if($regPayDueDate == date('Y-m-d')){
                        $_SESSION['message'] = 'Your monthly registration Fee payment is due today.';
                        $regPayDueDate = date('Y-m-d', strtotime("+ 1 month"));
                        $regFeePaid = 0;
                        $statement = $connection->prepare("UPDATE  seller  SET  regFeePaid = ?, regPayDueDate = ? WHERE sellerID = ?");
                        $statement->bind_param("iss", $regFeePaid, $regPayDueDate, $_SESSION['sid']);
                        $statement->execute();
                        $statement->close();
                    }elseif($currentDay >= $beforeDue){
                        $DueDate = date('d M Y', strtotime($regPayDueDate));
                        $_SESSION['message'] = "Your monthly registration fee payment will be due on $DueDate.";
                        
                    }
                    header("Location: item_list.html.php");
                }else{
                    echo "<script>alert('Unable to login. Check your email and password.');</script>";
                }
            }else{
                $statement->close();
                
                $statement = $connection->prepare("SELECT AucID, password FROM auctioneer WHERE email = ?");
                $statement->bind_param("s", $email);
                $statement->execute();
                $statement->bind_result($id, $pw);
                if($statement->fetch()){
                    if(password_verify($password, $pw)){
                        $_SESSION["aid"] = $id; 
                        header("Location: approve_items.html.php");
                    }else{
                        echo "<script>alert('Unable to login. Check your email and password.');</script>";
                    }
                }else{
                    echo "<script>alert('Unable to login. Check your email and password.');</script>";
                }
            }
        }
        
    }
?>

        <div class="user-form">
            <form method="post">
                    <div class="text">EMAIL:</div>
                    <input type="email" name="email" maxlength="50" required placeholder="someone@example.com"/>

                    <div class="text">PASSWORD:</div>
                    <input type="password" name="password" maxlength="100" required placeholder="******"/>

                    <input type="submit" name="login" value="Login" class="button smoothrectangle"/>
                    <input type="submit" name="cancel" value="Cancel" class="button smoothrectangle" formnovalidate />
            </form>
        </div>
<?php include 'includes/footer.php';?>