<?php
    $pageTitle = "Outstanding Payment Report";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    $statement = $connection->prepare('SELECT sellerID, sellerName, registrationFee, regPayDueDate FROM seller WHERE sellerID = ?');
    $statement->bind_param("s", $_SESSION['sid']);
    $statement->execute();
    $statement->bind_result($sellerID, $sellerName, $registrationFee, $dueDate);
    
    $statement->store_result();
    
    
    
    if(isset($_SESSION['sid'])):
    
    include 'includes/s_activities.html';
?>
    
    
<?php if($statement->num_rows > 0):?>
        <table class="table">
            <tr class="tableHead">
                <th>No.</th>
                <th>Seller ID</th>
                <th>Seller Name</th>
                <th>Registration Fee</th>
                <th>Due Date</th>
            </tr>
<?php
        
        $no = 1;
        while($statement->fetch()){
            ?>
            <tr>
                <td><?php htmlout($no); ?></td>
                <td><?php htmlout($sellerID); ?></td>
                <td><?php htmlout($sellerName); ?></td>
                <td>$<?php htmlout($registrationFee); ?></td>
                <td><?php htmlout($dueDate); ?></td>
            </tr>
            <?php
            $no++;
        }
        if(isset($_SESSION['message'])){
            $message = $_SESSION['message'];
            echo "<div style = 'width: 50%; margin: auto; margin-bottom: 10px;' class='warningalert'><span>$message</span></div>";
        }
        ?>
    
</table>
    


<?php
    else:
        echo '<p style="text-align: center;">There is no payment for you to pay at the moment.</p>';
    endif;
else:
    echo '<p style="text-align: center;">You must login as seller first if you want to view this page.</p>';
endif;
    include 'includes/footer.php';
?>