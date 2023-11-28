<?php include_once("database.php");
?>
<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $userId = $_SESSION["userId"];
    $productId = $_GET["productId"];
    $watchItemId = (array_values(runQuery("SELECT watchItemId FROM WatchItem WHERE userId = {$userId} AND productId = {$productId}")->fetch_assoc())[0]);
    // CHECK IF ONGOING
    $now = new DateTime();
    $auctionEndDatetime = new DateTime($productDetails["auctionEndDatetime"]);
    $auction_ended = $auctionEndDatetime < $now;
    if ($watchItemId !== null) {
        $watchItemIdObj = json_encode(array("operation" => "delete", "watchItemId" => $watchItemId));
        echo "<button class='btn btn-danger text-nowrap' hx-confirm='Are you sure you want to remove from watchlist?' hx-post='watchitem_button.php' hx-swap='outerHTML' hx-trigger='click' name='remove-watchitem' hx-vals=$watchItemIdObj id='remove-watchitem' <?php $auction_ended ? echo 'disabled'; : '' ?>>- Remove from watchlist</button>";
    } else {
        $watchItemObj = json_encode(array("operation" => "insert", "productId" => $productId));
        echo "<button class='btn btn-success text-nowrap' hx-post='watchitem_button.php' hx-swap='outerHTML' hx-trigger='click' name='add_watchitem' hx-vals=$watchItemObj <?php $auction_ended ? echo 'disabled'; : '' ?>>+ Add to watchlist</button>";
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // CHECK IF ONGOING
    $now = new DateTime();
    $auctionEndDatetime = new DateTime($productDetails["auctionEndDatetime"]);
    $auction_ended = $auctionEndDatetime < $now;
    $operation = $_POST['operation'];
    if ($operation == "delete") {
        $watchItemId = $_POST['watchItemId'];
        $productId = (array_values(runQuery("SELECT productId FROM WatchItem WHERE watchItemId = {$watchItemId} ")->fetch_assoc())[0]);
        $result = runQuery("DELETE FROM WatchItem WHERE watchItemId = {$watchItemId}");
        if ($result) {
            $watchItemObj = json_encode(array("operation" => "insert", "productId" => $productId));
            echo "<button class='btn btn-success text-nowrap' hx-post='watchitem_button.php' hx-swap='outerHTML' hx-trigger='click' name='add_watchitem' hx-vals=$watchItemObj <?php $auction_ended ? echo 'disabled'; : '' ?>>+ Add to watchlist</button>";
        }
    } else if ($operation == "insert") {
        $productId = $_POST["productId"];
        $userId = $_SESSION["userId"];
        $result = runQuery("INSERT INTO WatchItem (userId, productId) VALUES ('{$userId}', '{$productId}')");
        if ($result) {
            $watchItemId = (array_values(runQuery("SELECT watchItemId FROM WatchItem WHERE userId = {$userId} AND productId = {$productId}")->fetch_assoc())[0]);
            $watchItemIdObj = json_encode(array("operation" => "delete", "watchItemId" => $watchItemId));
            echo "<button class='btn btn-danger text-nowrap' hx-confirm='Are you sure you want to remove from watchlist?' hx-post='watchitem_button.php' hx-swap='outerHTML' hx-trigger='click' name='remove-watchitem' hx-vals=$watchItemIdObj id='remove-watchitem' <?php $auction_ended ? echo 'disabled'; : '' ?>>- Remove from watchlist</button>";
        }
    }
}
?>