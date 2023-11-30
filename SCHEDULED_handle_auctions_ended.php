<?php
include_once("database.php");
include_once("config.php");
include_once("utilities.php");

// Get all auctions that have ended
$winners_to_email = runQuery("WITH productsToEmail AS (
    SELECT productId 
    FROM `product` 
    WHERE auctionEndDatetime < DATE_ADD(NOW(), INTERVAL 1 HOUR) AND orderEmailSent = 0
    )
   SELECT MAX(amount) AS 'winningBidAmount', userId AS 'winningUser', productId
FROM bid
WHERE 
    bid.productId in (SELECT productId FROM productsToEmail)
GROUP BY productId
    ;
");

$product_ids_to_set_emailed = array();

//  FOR EACH OF THESE PRODUCTS, GET THE HIGHEST BIDDER AND SEND THEM AN EMAIL
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
    $product_ids_to_set_emailed[] = $productId;
    
}

// NOW HANDLE INFORMING EVERYONE WITH THAT PRODUCT ON THEIR WATCH LIST THAT IT'S ENDED
$watchlist_to_email = runQuery(
    "SELECT watchitem.userId, watchitem.productId, product.auctionEndDatetime, product.orderEmailSent
    FROM `watchitem`
    JOIN product ON watchitem.productId = product.productId
    WHERE auctionEndDatetime < DATE_ADD(NOW(), INTERVAL 1 HOUR) AND product.orderEmailSent IS NULL"
);

while ($row = $watchlist_to_email->fetch_assoc()) {
    $userId = $row['userId'];
    $productId = $row['productId'];

    $userDetails = runQuery("SELECT * FROM User WHERE userId = {$userId}")->fetch_assoc();  
    $productDetails = runQuery("SELECT * FROM Product WHERE productId = {$productId}")->fetch_assoc();

    $email = $userDetails['email'];
    $firstName = $userDetails['firstName'];
    $productName = $productDetails['name'];
    $productLink = "https://localhost/db-fundamentals/listing.php?productId={$productId}";

    // SEND EMAIL IF CONFIG.EMAIL_SENDING == True
    if ($EMAIL_SENDING) {


        $to = $email;
        $subject = "An auction you're watching has ended!";
        $message = "Hi {$firstName},\n\nOh no! 🎉🎉\n\nThe auction you were watching for {$productName} ({$productLink}) has ended.\n\nThanks,\nThe Db-Friends Team";
        $header = "From: anchit97123@gmail.com";
        if (!mail($to, $subject, $message, $header)) {
            $_SESSION['flash'] = ["type" => "warning", "message" => "Winning bid email failed to send to {$email}. You are only seeing this message because you are in debug mode."];
        }
    }
    // UPDATE THE PRODUCT TO SAY THAT THE EMAIL HAS BEEN SENT
    $product_ids_to_set_emailed[] = $productId;
}

// SET ALL THE PRODUCTS TO HAVE THE EMAIL SENT
foreach ($product_ids_to_set_emailed as $productId) {
    runQuery("UPDATE product SET orderEmailSent = 1 WHERE productId = {$productId}");
}