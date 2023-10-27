<?php
    session_start();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $pageTitle; ?></title>
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <link rel="stylesheet" href="css/datepicker.css" type="text/css">
        <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
        <script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script type="text/javascript">
            function showImage(input){
                    var url = input.value;
                    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                    if (input.files && input.files[0] && (ext === "gif" || ext === "png"
                            || ext === "jpeg" ||  ext === "jpg") ){
                            var reader = new FileReader();
                            reader.onload = function (e){
                                    document.getElementById("image").src = e.target.result;
                            };
                            reader.readAsDataURL(input.files[0]);
                    }else{
                            document.getElementById("image").src = "";
                            input.value = "";
                            alert("You must select an image file of type jpg, png or gif!");
                    }
            }
	</script>
        <script type="text/javascript">
        // When the document is ready
        $(document).ready(function () {

            $('#date').datepicker({
                format: "yyyy-mm-dd"
            });  

        });
        </script>
        <script type="text/javascript">

                function DropDown(el) {
                        this.dd = el;
                        this.initEvents();
                }
                DropDown.prototype = {
                        initEvents : function() {
                                var obj = this;

                                obj.dd.on('click', function(event){
                                        $(this).toggleClass('active');
                                        event.stopPropagation();
                                });	
                        }
                }

                $(function() {

                        var dd = new DropDown( $('#dd') );

                        $(document).click(function() {
                                // all dropdowns
                                $('.dropdown').removeClass('active');
                        });

                });

        </script>
    </head>
    <body>
    <div id="top_wrap"><div id="top">    
        <div class="navi" id="menu">
            <ul> 
                <?php
                    if(empty($_SESSION['aid']) && empty($_SESSION['sid']) && empty($_SESSION['bid'])){
                        echo '<li><a href="index.php"><span></span>Sign In</a></li>
                        <li><a href="register.html.php"><span></span>Registration</a></li>';
                        echo '<li><a href="browse_items.html.php"><span></span>Items</a></li>';
                    }
                
                    if(isset($_SESSION['aid'])){
                        echo '<li><a href="browse_items.html.php"><span></span>Items</a></li>';
                        echo '<li><a href="auc_activities.html.php"><span></span>Activities</a></li>';
                        echo '<li><a href="logout.php" title="Sign Out"><span></span>Sign Out</a></li>';
                    }elseif(isset($_SESSION['bid'])){
                        echo '<li><a href="browse_items.html.php"><span></span>Items</a></li>';
                        echo '<li><a href="buyer_profile.html.php"><span></span>Profile</a></li>';
                        echo '<li><a href="logout.php"><span></span>Sign Out</a></li>';
                    }elseif(isset($_SESSION['sid'])){
                        echo '<li><a href="browse_items.html.php"><span></span>Items</a></li>';
                        echo '<li><a href="item_list.html.php"><span></span>Manage Items</a></li>';
                        echo '<li><a href="seller_profile.html.php"><span></span>Profile</a></li>';
                        echo '<li><a href="logout.php"><span></span>Sign Out</a></li>';   
                    }  
                ?>
            </ul>
        </div>
    </div></div> 
    	<div class="main-wrap">
            <div class="logo"></div>
            <div class="form-header">
                <h2><?php echo $pageTitle; ?></h2>
                <div class="strip"></div>
            </div>
            
