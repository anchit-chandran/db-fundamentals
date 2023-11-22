<?php
include_once("database.php");
include_once("config.php");
include_once("utilities.php");

// Get all auctions that have ended
$winners_to_email = runQuery("WITH productsToEmail AS (
    SELECT productId 
    FROM `product` 
    WHERE auctionEndDatetime < NOW() AND orderEmailSent = 0
    )
   SELECT MAX(amount) AS 'winningBidAmount', userId AS 'winningUser', productId
FROM bid
WHERE 
    bid.productId in (SELECT productId FROM productsToEmail)
GROUP BY productId
    ;
");

//  FOR EACH OF THESE PRODUCTS, GET THE HIGHEST BIDDER


while ($row = $winners_to_email->fetch_assoc()) {
    $productId = $row['productId'];

    $userDetails = runQuery("SELECT * FROM User WHERE userId = {$row['winningUser']}")->fetch_assoc();
    $productDetails = runQuery("SELECT * FROM Product WHERE productId = {$productId}")->fetch_assoc();

    $email = $userDetails['email'];
    $firstName = $userDetails['firstName'];
    $productName = $productDetails['name'];
    $winningAmount = $row['winningBidAmount'];

    // SEND EMAIL IF CONFIG.EMAIL_SENDING == True
    if ($EMAIL_SENDING) {


        $to = $email;
        $subject = "You've won!";
        $feedback_link = "https://www.localhost/db-fundamentals/feedback.php?product_id={$productId}";
        $message = "Hi {$firstName},\n\nCongratulations! 🎉🎉\n\nYou won the auction for {$productName} with your winning bid of {$winningAmount}!\n\nPayment will be taken using registered details and the product will be send to your registered address.\n\nPlease consider leaving feedback using: {$feedback_link}.\n\nThanks,\nThe Db-Friends Team";
        $header = "From: anchit97123@gmail.com";
        if (!mail($to, $subject, $message, $header)) {
            $_SESSION['flash'] = ["type" => "warning", "message" => "Winning bid email failed to send to {$email}. You are only seeing this message because you are in debug mode."];
        }
    }

    // UPDATE THE PRODUCT TO SAY THAT THE EMAIL HAS BEEN SENT
    $updateProduct = runQuery("UPDATE Product SET orderEmailSent = 1 WHERE productId = {$productId}");

    // echo 'UPDATED PRODUCT:';
    // echo '<pre>';
    // print_r(runQuery("SELECT * FROM Product WHERE productId = {$productId}")->fetch_assoc());
    // echo '</pre>';
}
