<?php
        $pageTitle = "Update Category";
        include 'includes/header.php';
        
	require "includes/db.inc.php";

	$categoryName="";
        
	$connection = new mysqli($host, $db_user, $db_password, $database);
        
	if (isset($_POST['update'])) {
            $categoryName= $_POST['categoryName'];
            
            $statement = $connection->prepare("UPDATE category SET categoryName = ?
                        WHERE categoryID = ?");
                $statement->bind_param("si", $categoryName, $_GET['cid']);
                $statement->execute();
                $error = $statement->error;
                $statement->close();
            
            if(empty($error)){
                echo "<script>alert('UPDATE SUCCESSFUL!!!'); location.assign('category_list.html.php');</script>";
            }else{
                echo "<script>alert('$error');</script>";
            }
                
	}
        if(isset($_GET['cid'])){
            $statement = $connection->prepare("SELECT categoryName FROM category WHERE categoryID = ?");
            $statement->bind_param("i", $_GET['cid']);
            $statement->execute();
            $statement->bind_result($categoryName);
            $statement->fetch();
            $statement->close();
        }
        
        if(isset($_POST['cancel'])){
            echo "<script>location.assign('category_list.html.php');</script>";
        }

?>

    <div class="user-form">
        <form method="post" action="">
            <div class="text">CATEGORY NAME: </div>
            <input type="text" name="categoryName" id="categoryname" value="<?php htmlout($categoryName); ?>" placeholder="Category Name" maxlength="20"/>

            <input type="submit" name="update" value="Update" class="button smoothrectangle" />
            <input type="submit" name="cancel" value="Cancel" formnovalidate  class="button smoothrectangle"/>

        </form>
    </div>

    <?php include 'includes/footer.php';?>