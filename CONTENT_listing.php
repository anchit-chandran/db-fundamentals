<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // FETCH PRODUCT DETAILS
  $productId = $_GET["item_id"];
  $productDetails = runQuery("
    SELECT * 
    FROM Product
    WHERE productId='{$productId}';
  ")->fetch_assoc();

  // FETCH BIDS FOR PRODUCT
  $bids = runQuery("
    SELECT * 
    FROM Bid
    WHERE productId='{$productId}';
  ");
  $n_bids = $bids->num_rows;
}



?>

<div class="row">
  <div class="col pt-3">
    <div class="row">
      <div class="col">
        <h1>Make a bid</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <h2 class="fw-lighter"><?php echo $productDetails["name"] ?></h2>
        <p class="text-muted">Auction started at <?php echo $productDetails["auctionStartDatetime"]?>.</p>
        <img src=<?php echo "{$productDetails['image']}" ?> alt="">
        <p><span class="fw-bold">Description:</span> <?php echo $productDetails["description"] ?></p>
        <p><span class="fw-bold">Condition:</span> <?php echo $productDetails["state"] ?></p>
        <h3>Bid History -
          <?php

          if ($n_bids == 1) {
            echo "1 Bid";
          } else {
            echo $n_bids . " Bids";
          }
          ?>
        </h3>
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Date & Time</th>
              <th>Amount</th>
              <th>Bidder</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">18 Oct 1997 10:45</th>
              <td>£2180</td>
              <td>3</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col">
        <button class="btn btn-secondary">+ Add to watchlist</button>
        <p>This auction ends at <?php echo $productDetails["auctionEndDatetime"]?>.</p>
        <p>Starting price: £<?php echo $productDetails["startPrice"] ?></p>
        <form action="#" method="post">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">£</span>
            <input type="text" class="form-control" placeholder="Bid amount" aria-label="bid-amount" aria-describedby="bid-amount">
          </div>
          <button type="submit" class="btn btn-primary">Place Bid</button>
        </form>
      </div>
    </div>
  </div>
</div>