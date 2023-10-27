<?php
    $pageTitle = "Categories";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    try {
        $r = $connection->query("SELECT * FROM category");
        
    } catch (Exception $e) {
        echo "<script>alert('Error fetching categories from the database!!!');</script>";
    }
    
    foreach ($r as $row) {
        $categories[] = array(
            'categoryID' => $row['categoryID'], 
            'categoryName' => $row['categoryName']);
    }
    
    if(isset($_SESSION['aid'])):
        
    include 'includes/a_activities.html';
    
?>
  
    <p><a class="button smoothrectangle" href="category_register.html.php" style="margin: 400px; ">Add New Category</a></p>
<?php 
    if(isset($categories)): ?>
    <table class="table">
        <tr class="tableHead">
            <th>No.</th>
            <th>Category Name</th>
            <th>Action</th>
        </tr>
        <?php
            
            $no = 1;
            foreach($categories as $c):
                

        ?>
        <tr>
            <td><?php htmlout($no); ?></td>
            <td><?php htmlout($c['categoryName']); ?></td>
            <td><a href="category_update.html.php?cid=<?php htmlout($c['categoryID']); ?>" class="button">Update</a>
                <a href="delete_confirmation.html.php?cid=<?php htmlout($c['categoryID']); ?>" class="button">Delete</a></td>
        </tr>
        <?php
                $no++;
            endforeach;
        ?>
    </table>
<?php endif;
    else:
        echo '<p style="text-align: center;">You must login as the auctioneer first if you want to view this page.</p>';
    endif;
include 'includes/footer.php';

?>
    	
