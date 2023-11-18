<?php
// This is were you make the content of the profile page, but it is linked to the profile.php page
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

?>
<div class="container">
    <div class="col">
                <h2>Profile Page</h2>
                    <p> 
                        
                    </p>
    </div>
</div>

<div class="container">
    <h4>Personal Info</h4>
        <div class="row">
            <div class="col-6 col-md-4">
                <?php
                    echo 'Name:'
                ?>
            </div>
            <div class="col-md-8">
                <?php
                    $getUserID = runQuery("SELECT firstName, lastName FROM User WHERE userId={$_SESSION['userId']}");
                    echo $getUserID;
                ?>
            </div>
        
        </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-6 col-md-4">
            <?php
                echo 'Name:'
            ?>
        </div>
        <div class="col-md-8">
            <?php
                echo "hi"
                $getAddressOne = runQuery("SELECT address_1 FROM Address WHERE userId={$_SESSION['userId']}");
                echo $getAddressOne;
            ?>
        </div>
       
    </div>
</div>