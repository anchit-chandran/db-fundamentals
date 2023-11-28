<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  // FETCH PRODUCT DETAILS
  $productId = $_GET["productId"];
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

  // CHECK IF ONGOING
  $now = new DateTime();
  $auctionEndDatetime = new DateTime($productDetails["auctionEndDatetime"]);
  $auction_ended = $auctionEndDatetime < $now;
}

  // USER DETAILS
  $user = runQuery("SELECT * FROM User WHERE userId = {$productDetails['userId']}")->fetch_assoc();

  $title = $auction_ended ? "Auction Ended" : "Auction Ongoing";

?>

<div class="row">
  <div class="col pt-3">
    <div class="row">
      <div class="col mb-3">
        <h1><?php echo $title . " - " . $productDetails["name"] ?></h1>
      </div>
    </div>
    <div class="row">
      <div class="col-8">        
        <p><span class="fw-bold">Description:</span> <?php echo $productDetails["description"] ?></p>
        <p><span class="fw-bold">Condition:</span> <?php echo $productDetails["state"] ?></p>
        <?php
        if ($productDetails["image"] != null) {
          $image = $productDetails["image"];
          $base64image = base64_encode($image);

          echo "<div class='d-flex' width='400' height='400'><img src='data:image/jpeg;base64," . $base64image . "' alt='Blob Image' style='object-fit:contain' width='400' height='400'></div>";
        } else {
          echo "<p>No image uploaded with this listing</p>";
        }
        ?>
        <p class="text-muted">Auction started at <?php echo $productDetails["auctionStartDatetime"] ?>.</p>


        <h3>Seller Information</h3>
        <p>Created by <?php 
        $userId = $user["userId"];
        $userFirstName = $user["firstName"];
        echo "<a href='http://localhost/db-fundamentals/profile.php?userId={$userId}'>{$userFirstName}</a>";        
        ?>
        </p>

        <p>
        Average seller rating:
        <?php 
        $avgSellerRating = runQuery(
          "SELECT ROUND(AVG(rating), 1) AS avgRating
          FROM Product P
          JOIN Feedback F
          ON P.productId = F.productId
          WHERE P.userId = {$userId};
          "
        )->fetch_assoc()["avgRating"];
        if ($avgSellerRating) {
          $rating = $avgSellerRating;
          include("CONTENT_rating.php");
          echo "( {$avgSellerRating} / 5 )";
        } else {
          echo "no ratings yet";
        }
        ?>
        </p>
        
        

        

        <?php 
          $feedback = runQuery(
            "SELECT *
            FROM Feedback
            WHERE productId = {$productId}"
          )->fetch_assoc();
          if ($feedback != null) {
            include("CONTENT_listing_feedback.php");
          }
        ?>

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
                $userId = $row['userId'];
                $userDetails = runQuery("SELECT * FROM User WHERE userId = {$userId}")->fetch_assoc();

                $userFirstName = (array_values(runQuery("SELECT firstName FROM User WHERE userId = " . $row['userId'])->fetch_assoc())[0]);
                $userLastName = (array_values(runQuery("SELECT lastName FROM User WHERE userId = " . $row['userId'])->fetch_assoc())[0]);
                echo "<tr>
                  <td>{$row['bidTime']}</th>
                  <td>£{$row['amount']}</td>
                  <td><a href='http://localhost/db-fundamentals/profile.php?userId={$userId}'>{$userFirstName} {$userLastName}</a></td>
                </tr>";
              }
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="col">
        <?php $user_logged_in = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) ?>
        <?php if (!$user_logged_in) {
          echo "<button class='btn btn-info text-nowrap'>Log in to add items to watch list</button>";
        } else {
          $userId = $_SESSION["userId"];
          $watchItemId = (runQuery("SELECT watchItemId FROM WatchItem WHERE userId = {$userId} AND productId = {$productId}")->fetch_assoc());
          if ($watchItemId !== null) {

            $watchItemIdObj = json_encode(array("operation" => "delete", "watchItemId" => array_values($watchItemId)));

            echo "<button class='btn btn-danger text-nowrap' hx-confirm='Are you sure you want to remove from watchlist?' hx-post='watchitem_button.php' hx-swap='outerHTML' hx-trigger='click' name='remove-watchitem' hx-vals=$watchItemIdObj id='remove-watchitem'>- Remove from watchlist</button> <br>";
          } else {
            $watchItemButtonDisabled = $auction_ended ? "disabled" : "";
            $watchItemObj = json_encode(array("operation" => "insert", "productId" => $productId));
            echo "<button class='btn btn-success text-nowrap' hx-post='watchitem_button.php' hx-swap='outerHTML' hx-trigger='click' name='add_watchitem' hx-vals=$watchItemObj {$watchItemButtonDisabled}>+ Add to watchlist</button>
            <br>";
          }
        } ?>
        <?php
        $end_date_str = $productDetails["auctionEndDatetime"];
        $now = new DateTime();
        $end_date = datetime::createFromFormat('Y-m-d H:i:s', $end_date_str);
        if ($now > $end_date) {
          echo "<p>Auction ended at {$productDetails['auctionEndDatetime']}</p>";
        } else {
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
        <?php $user_logged_in = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) ?>
        <form action="place_bid.php" method="post">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">£</span>
            <input name="product_id" type="hidden" value=<?php echo $productId; ?>>
            <input name="user_id" type="hidden" value=<?php if (isset($_SESSION['logged_in'])) {
                                                        echo $_SESSION['userId'];
                                                      } ?>>
            <input name="bid_amount" type="number" step="0.01" min="0" class="form-control" placeholder="Bid amount" aria-label="bid-amount" aria-describedby="bid-amount" <?php if (!$user_logged_in or $auction_ended) {
                                                                                                                                                                              echo "disabled";
                                                                                                                                                                            } ?>>
          </div>
          <button type="submit" class="btn <?php if ($user_logged_in) {
                                              echo "btn-primary";
                                            } else {
                                              echo "btn-secondary";
                                            } ?>" <?php if (!$user_logged_in or $auction_ended) {
                                                                                                                              echo "disabled";
                                                                                                                            } ?>>
            Place Bid
          </button>
          <?php if (!$user_logged_in) {
            echo "<p class='text-danger small'>You must log in before placing a bid.</p>";
          } ?>

        </form>
      </div>
    </div>
  </div>
</div>