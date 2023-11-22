<?php

include_once 'database.php';

$product_id_in_param = false;
$product_found = false;
$auction_ended = false;
$highest_bid_at_least_reserve = false;
$user_logged_in = false;
$user_matches_highest_bidder = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $product_id_in_param = isset($_GET['product_id']);
    if ($product_id_in_param) {
        $product_id = $_GET['product_id'];
        $query = "SELECT * FROM Product WHERE productId = {$product_id}";
        $result = runQuery($query)->fetch_assoc();
        $product_found = $result != null;
        if ($product_found) {
            $end_date_str = $result['auctionEndDatetime'];
            $now = new DateTime();
            $end_date = datetime::createFromFormat('Y-m-d H:i:s', $end_date_str);
            $auction_ended = $now > $end_date;
        }
    }

    if ($product_found) {
        // Fetch max bid of product to compare against current bid
        $highest_bid = floatval(runQuery("SELECT MAX(amount) FROM Bid WHERE productId = " . $product_id)->fetch_assoc()["MAX(amount)"]);
        if ($highest_bid >= floatval($result['reservePrice']) && $highest_bid >= floatval($result['startPrice'])) {
            $highest_bid_at_least_reserve = true;
        }
        $highest_bid_user_id = runQuery("SELECT userId FROM Bid WHERE productId = {$product_id} ORDER BY amount DESC LIMIT 1")->fetch_assoc();
        if ($highest_bid_user_id != null) {
            $highest_bid_user_id = $highest_bid_user_id['userId'];
        }
    }

    $user_logged_in = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true);

    if ($product_found && $user_logged_in) {
        $user_id = $_SESSION["userId"];
        $user_matches_highest_bidder = $user_id == $highest_bid_user_id;
    }

    if (!$product_id_in_param) {
        echo "<div class='alert alert-warning mt-4 mb-0' role='alert'>The product id parameter is missing from URL, so you cannot access the feedback page.</div>";
    } else if (!$product_found) {
        echo "<div class='alert alert-warning mt-4 mb-0' role='alert'>Product not found, so you cannot access the feedback page.</div>";
    } else if (!$user_logged_in) {
        echo "<div class='alert alert-warning mt-4 mb-0' role='alert'>You must log in to access the feedback page.</div>";
    } else if (!$auction_ended) {
        echo "<div class='alert alert-warning mt-4 mb-0' role='alert'>The auction for this product has not yet ended, so you cannot access the feedback page.</div>";
    } else if (!$highest_bid_at_least_reserve) {
        echo "<div class='alert alert-warning mt-4 mb-0' role='alert'>The product did not sell as bids were too low, so you cannot access the feedback page.</div>";
    } else if (!$user_matches_highest_bidder) {
        echo "<div class='alert alert-warning mt-4 mb-0' role='alert'>You are not the highest bidder for this product, so you cannot access the feedback page.</div>";
    } else {
        include 'CONTENT_feedback_form.php';
    }
}

?>