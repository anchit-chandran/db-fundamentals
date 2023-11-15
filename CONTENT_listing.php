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
    WHERE productId='{$productId}'
    ORDER BY amount DESC;
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
        <p class="text-muted">Auction started at <?php echo $productDetails["auctionStartDatetime"] ?>.</p>
        <img src=<?php echo "{$productDetails['image']}" ?> alt="">
        <p><span class="fw-bold">Description:</span> <?php echo $productDetails["description"] ?></p>
        <p><span class="fw-bold">Condition:</span> <?php echo $productDetails["state"] ?></p>
        <h3>Most Recent Bids -
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
            <?php

            if ($bids) {
              while ($row = $bids->fetch_assoc()) {
                echo "<tr>
                  <th scope='row'>{$row['bidTime']}</th>
                  <td>{$row['amount']}</td>
                  <td>{$row['userId']}</td>
                </tr>";
              }
            }


            ?>
          </tbody>
        </table>
      </div>
      <div class="col">
        <button class="btn btn-secondary">+ Add to watchlist</button>
        <?php
          $end_date_str = $productDetails["auctionEndDatetime"];
          $now = new DateTime();
          $end_date = datetime::createFromFormat('Y-m-d H:i:s', $end_date_str);
          if ($now->format('Y-m-d H:i:s') > $end_date->format('Y-m-d H:i:s')) {
              echo "<p>Auction ended at {$productDetails['auctionEndDatetime']}</p>";
          }
          else {
              $time_to_end = date_diff($now, $end_date);
              $time_remaining = display_time_remaining($time_to_end);
              echo "<p>Auction ends at {$productDetails['auctionEndDatetime']} ({$time_remaining} from now)</p>";
          } 
        
        ?>
        <p>Starting price: £<?php echo $productDetails["startPrice"] ?></p>
        <p>Highest bid: <?php 
          $highest_bid = (array_values(runQuery("SELECT MAX(amount) FROM Bid WHERE productId = " . $productId)->fetch_assoc())[0]);
          if ($highest_bid == NULL) {
            echo "No bids yet";
          } else {
            echo "£" . $highest_bid;
          }
          
          ?></p>
        <?php $user_logged_in = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)?>
        <form action="#" method="post">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">£</span>
            <input type="text" class="form-control" placeholder="Bid amount" aria-label="bid-amount" aria-describedby="bid-amount"
              <?php if (!$user_logged_in){echo "disabled";}?>
            >
          </div>
          <button type="submit" class="btn <?php if ($user_logged_in){echo "btn-primary";} else {echo "btn-secondary";}?>" 
            <?php if (!$user_logged_in){echo "disabled";}?>
          >
              Place Bid
            </button>
          <?php if (!$user_logged_in){echo "<p class='text-danger small'>You must log in before placing a bid.</p>";}?>

        </form>
      </div>
    </div>
  </div>
</div>