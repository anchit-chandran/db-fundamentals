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
    $highest_bid = floatval(runQuery("SELECT MAX(amount) FROM Bid WHERE productId = " . $product_id)->fetch_assoc()[0]);
    $highest_bid_user = runQuery("SELECT userId FROM Bid WHERE amount = " . $highest_bid)->fetch_assoc()[0];

    $starting_price = runQuery("SELECT startPrice FROM Product WHERE productId = " . $product_id)->fetch_assoc()[0];
    $end_date_str = runQuery("SELECT auctionEndDatetime FROM Product WHERE productId = " . $product_id)->fetch_assoc()[0];
    $now = new DateTime();
    $end_date = datetime::createFromFormat('Y-m-d H:i:s', $end_date_str);

    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if ($now > $end_date) {
        $_SESSION["flash"] = ["type" => "warning", "message" => "Failed to add bid. The auction has ended."];
        header("Location: listing.php?productId={$product_id}");
    } else if ($bid_amount <= $starting_price) {
        $_SESSION["flash"] = ["type" => "warning", "message" => "Failed to add bid. Your bid needs to be greater than the starting price."];
        header("Location: listing.php?productId={$product_id}");
    } else if ($bid_amount <= $starting_price) {
        $_SESSION["flash"] = ["type" => "warning", "message" => "Failed to add bid. Your bid needs to be greater than the starting price."];
        header("Location: listing.php?productId={$product_id}");
    } else if ($bid_amount <= $highest_bid) {
        $_SESSION["flash"] = ["type" => "warning", "message" => "Failed to add bid. Your bid needs to be greater than the highest bid."];
        header("Location: listing.php?productId={$product_id}");
    } else if ($highest_bid_user == $user_id) {
        $_SESSION["flash"] = ["type" => "warning", "message" => "Failed to add bid. You cannot bid against yourself as the highest bidder."];
        header("Location: listing.php?productId={$product_id}");
    } else {
        $add_bid = "INSERT INTO Bid (amount, productId, userId)
            VALUES ({strval($bid_amount)}, {$product_id}, {$user_id});";
        runQuery($add_bid);
        $_SESSION["flash"] = ["type" => "success", "message" => "Bid added successfully"];
        header("Location: listing.php?productId={$product_id}");
    }  

}