<?php
// include_once("CONTENT_header.php");
include_once("database.php");
// include_once("utilities.php");

// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.

?>

<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $bid_amount = floatval($_POST["bid_amount"]);
    $user_id = $_POST["user_id"];

    // Fetch max bid of product to compare against current bid
    $highest_bid = floatval(array_values(runQuery("SELECT MAX(amount) FROM Bid WHERE productId = " . $product_id)->fetch_assoc())[0]);
    
    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if ($bid_amount > $highest_bid) {
        $add_bid = "INSERT INTO Bid (amount, productId, userId)
            VALUES ({strval($bid_amount)}, {$product_id}, {$user_id});";
        runQuery($add_bid);
        $_SESSION["flash"] = ["type" => "success", "message" => "Bid added successfully"];
        header("Location: listing.php?item_id={$product_id}");
    } else {
        $_SESSION["flash"] = ["type" => "warning", "message" => "Failed to add bid"];
        header("Location: listing.php?item_id={$product_id}");
    }  

}