<?php

include_once 'database.php';

$product_id_in_param = false;
$product_found = false;
$auction_not_yet_ended = false;
$user_logged_in = false;
$user_matches_highest_bidder = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $product_id_in_param = isset($_GET['product_id']);
    if (!$product_id_in_param) {
        echo "Error: product_id missing ";
    } else {
        $product_id = $_GET['product_id'];
        $query = "SELECT * FROM Product WHERE productId = {$product_id}";
        $result = runQuery($query)->fetch_assoc();
        $product_found = $result != null;
        if (!$product_found) {
            echo "Error: product_id not found ";
        } else {
            $end_date_str = $result['auctionEndDatetime'];
            $now = new DateTime();
            $end_date = datetime::createFromFormat('Y-m-d H:i:s', $end_date_str);
            $auction_not_yet_ended = $now < $end_date;
            if ($auction_not_yet_ended) {
                echo "Error: auction has not yet ended ";
            }
        }
    }

    if ($product_found) {
        // Fetch max bid of product to compare against current bid
        $highest_bid = floatval(runQuery("SELECT MAX(amount) FROM Bid WHERE productId = " . $product_id)->fetch_assoc()["MAX(amount)"]);
        $highest_bid_user = runQuery("SELECT userId FROM Bid WHERE productId = {$product_id} ORDER BY amount DESC LIMIT 1")->fetch_assoc();
        if ($highest_bid_user != null) {
            $highest_bid_user = $highest_bid_user['userId'];
        }
    }

    $user_logged_in = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true);
    if (!$user_logged_in) {
        echo "Error: user not logged in";
    } else {
        $user_id = $_SESSION["userId"];
        if ($highest_bid_user != $user_id) {
            echo "Error: user is not the highest bidder";
        }
    }

    // if ($end_date > $now) {
    //     $_SESSION["flash"] = ["type" => "warning", "message" => "Failed to add bid. The auction has ended."];
    //     header("Location: listing.php?productId={$product_id}");
    // } else if ($bid_amount <= $starting_price) {

    //     $_SESSION["flash"] = ["type" => "warning", "message" => "Failed to add bid. Your bid needs to be greater than the starting price."];
    //     header("Location: listing.php?productId={$product_id}");
    // } else if ($bid_amount <= $highest_bid) {
    //     $_SESSION["flash"] = ["type" => "warning", "message" => "Failed to add bid. Your bid needs to be greater than the highest bid."];
    //     header("Location: listing.php?productId={$product_id}");
    // } else if ($highest_bid_user == $user_id) {
    //     $_SESSION["flash"] = ["type" => "warning", "message" => "Failed to add bid. You cannot bid against yourself as the highest bidder. "];
    //     header("Location: listing.php?productId={$product_id}");
    // } else {
    //     $add_bid = "INSERT INTO Bid (amount, productId, userId)
    //         VALUES ({strval($bid_amount)}, {$product_id}, {$user_id});";
    // }

}


?>

<div class="container">

    <?php
    include_once 'CONTENT_feedback_form.php';
    ?>


</div>