<?php 
    $pageTitle = "New Category";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    //$categoryID = "";
    $categoryName = "";
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    if(isset($_POST['add'])){
        $categoryName = $_POST['categoryName'];
        $error = '';
        $statement = $connection->prepare("INSERT INTO category(categoryName) VALUES(?)");
        $statement->bind_param("s", $categoryName);
        $statement->execute();
        $error = $statement->error;
        $categoryID = $statement->insert_id;
        if (empty($error)) {
            echo "<script>alert('CATEGORY REGISTERED'); location.assign('category_list.html.php');</script>";
        }else{
            echo "<script>alert('REGISTRATION ERROR.');</script>";
            
        }
        $statement->close();
    }
    
    if(isset($_POST['cancel'])){
        header("Location: category_list.html.php");
    }
    
?>

    <div class="user-form">
        <form method="post" action="">
            <div class="text">CATEGORY NAME: </div>
            <input type="text" name="categoryName" id="categoryname" value="<?php htmlout($categoryName); ?>" placeholder="Category Name" maxlength="20"/>

            <input type="submit" name="add" value="Add" class="button smoothrectangle" />
            <input type="submit" name="cancel" value="Cancel" class="button smoothrectangle"/>

        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
