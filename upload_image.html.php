<?php   
        $pageTitle = "Upload More Images";
        include 'includes/header.php';
        
	require "includes/db.inc.php";
        
        $connection = new mysqli($host, $db_user, $db_password, $database);
        
        $imageName = "";
        $itemNo = "";
        if(isset($_POST['upload'])){
            $imageName = $_FILES["image"]["name"];
            $itemNo = $_GET['ino'];
            
            $statement = $connection->prepare("INSERT INTO itemattachment(itemNo, imageName) 
                    VALUES (?, ?)");
            $statement->bind_param("is", $itemNo, $imageName);
            $statement->execute();
            $imageID = $statement->insert_id;
            $statement->close();

            $newname = "i".$itemNo."-".$imageID.".".pathinfo($imageName, PATHINFO_EXTENSION);

            $statement = $connection->prepare("UPDATE itemattachment SET imageName = ? WHERE imageID = ?");
            $statement->bind_param("si", $newname, $imageID);
            $statement->execute();
            
            move_uploaded_file($_FILES["image"]["tmp_name"], $imagepath.$newname);
            $statement->close();
            
            echo "<script>alert('UPLOADED');</script>";
        }
	
        if(isset($_POST['cancel'])){
            echo "<script>alert('Canceled!!'); window.history.go(-2);</script>";
        }
?>

<div class="user-form" style="width: 360px;">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="imageUpload">
                <div class="text">Upload more image: </div>
                <input type="file" name="image" title="Choose Image." />
                
                <input type="submit" name="upload" value="Upload" class="button smoothrectangle"/>
                <input type="submit" name="cancel" value="Cancel" class="button smoothrectangle"/>
                </br>
                
                <?php
                    $statement = $connection->prepare("SELECT imageID, imageName FROM itemattachment WHERE itemNo = ?");
                    $statement->bind_param("i", $_GET['ino']);
                    $statement->execute();
                    $statement->bind_result($imageID, $imageName);
                    while($statement->fetch()){
                        ?>
                        
                <img src="images/items/<?php htmlout($imageName); ?>" alt="<?php htmlout($imageName); ?>" style="width: 90px; padding-right: 7px; margin-top: 10px;"/>
                            
                <a href="delete_confirmation.html.php?imageID=<?php htmlout($imageID); ?>">DELETE</a>
                        
                        <?php
                    }
                    $statement->close();
                ?>
                
            </div>

        </form>
    </div>


    <?php include 'includes/footer.php'; ?>
