<?php
// include_once("CONTENT_header.php");
include_once("database.php");
include_once("config.php");
// include_once("utilities.php");
?>

<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $rating = $_POST["rating"];
    $user_id = $_POST["user_id"];
    $comment = $_POST["comment"];
    $comment = !empty($comment) ? "'$comment'" : "NULL";  // set to "NULL" string if empty
    $product_id = $_POST["product_id"];

    // Clear previous feedback of product
    runQuery(
        "DELETE
        FROM Feedback
        WHERE productId = {$product_id} AND userId = {$user_id}"
    );
    
    runQuery(
        "INSERT INTO Feedback (comment, rating, productId, userId) 
        VALUES ({$comment}, {$rating}, {$product_id}, {$user_id})"
    );

    $_SESSION["flash"] = ["type" => "success", "message" => "Product feedback added/updated."];
    header("Location: listing.php?productId={$product_id}");
}
