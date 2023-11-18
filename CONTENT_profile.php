<?php
// This is were you make the content of the profile page, but it is linked to the profile.php page
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");


$getUserID = runQuery("SELECT firstName, lastName FROM User WHERE userId={$_SESSION['userId']}");
echo $getUserID;
  

?>

<div class="row">
    <div class="col">
        <h2>Profile Page</h2>
            <p></p>
    </div>
</div>