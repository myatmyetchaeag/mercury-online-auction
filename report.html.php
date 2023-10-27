<?php
    $pageTitle = "Monthly Income Report";
    include 'includes/header.php';
    
    require 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    if(isset($_SESSION['aid'])):
    
    include 'includes/a_activities.html';
?>
<div class="user-form" style="border-style: none;">
    <form method="post" action="monthlyincomereport.php">
    <div class="text">MONTH: </div>
    <select name="month">
        <option value="1">January</option>
        <option value="2">February</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">June</option>
        <option value="7">July</option>
        <option value="8">August</option>
        <option value="9">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
    </select>
    <div class="text">YEAR: </div>
    <select name="year">
        <option value="2016">2016</option>
        <option value="2017">2017</option>
        <option value="2018">2018</option>
        <option value="2019">2019</option>
        <option value="2020">2020</option>
    </select>
    
    <input type="submit" name="report" value="Report" class="button smoothrectangle" style="float: right;">
   

</form>
</div> 

<?php 
else:
    echo '<p style="text-align: center;">You must login as the auctioneer first if you want to view this page.</p>';
endif;
include 'includes/footer.php';?>