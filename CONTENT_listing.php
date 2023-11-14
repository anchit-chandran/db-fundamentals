<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

// FETCH PRODUCT DETAILS
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $productId = $_GET["item_id"];
  $productDetails = runQuery("
    SELECT * 
    FROM Product
    WHERE productId='{$productId}'
  ")->fetch_assoc();
}

?>

<div class="row">
  <div class="col pt-3">
    <div class="row">
      <div class="col">
        <h2>Make a bid</h2>
      </div>
    </div>
    <div class="row">
      <div class="col">
        
      </div>
    </div>
  </div>
</div>