<?php include_once("header.php")?>

<div class="container my-5">

<?php
include_once("database.php");
session_start();
$userId = $_SESSION["userId"];
$name = $_POST["auctionTitle"];
$desc = $_POST["auctionDetails"];
$category = $_POST["auctionCategory"];
$subcategory = $_POST["auctionSubCategory"];
$condition = $_POST["auctionCondition"];
$auctionStart = $_POST["auctionStartDate"];
$auctionEnd = $_POST["auctionEndDate"];
$startPrice = $_POST["auctionStartPrice"];
$reservePrice = $_POST["auctionReservePrice"];
$iamge = $_POST["auctionImage"];
echo "User ID: " . $userId;
// UserID not there
// need to add validation to length of inputs?

// This function takes the form data and adds the new auction to the database.

/* TODO #1: Connect to MySQL database (perhaps by requiring a file that
            already does this). */

/* TODO #2: Extract form data into variables. Because the form was a 'post'
            form, its data can be accessed via $POST['auctionTitle'], 
            $POST['auctionDetails'], etc. Perform checking on the data to
            make sure it can be inserted into the database. If there is an
            issue, give some semi-helpful feedback to user. */


/* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */
            

// If all is successful, let user know.

echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');
echo('<div class="text-center">Here <?php echo $userId ?></div>');

?>
</div>


<?php include_once("footer.php")?>