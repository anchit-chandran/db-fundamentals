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
                $userFirstName = (array_values(runQuery("SELECT firstName FROM User WHERE userId = " . $row['userId'])->fetch_assoc())[0]);
                $userLastName = (array_values(runQuery("SELECT lastName FROM User WHERE userId = " . $row['userId'])->fetch_assoc())[0]);
                echo "<tr>
                  <th scope='row'>{$row['bidTime']}</th>
                  <td>£{$row['amount']}</td>
                  <td>{$userFirstName} {$userLastName}</td>
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
            $watchItemObj = json_encode(array("operation" => "insert", "productId" => $productId));
            echo "<button class='btn btn-success text-nowrap' hx-post='watchitem_button.php' hx-swap='outerHTML' hx-trigger='click' name='add_watchitem' hx-vals=$watchItemObj>+ Add to watchlist</button>
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
            <input name="bid_amount" type="number" step="0.01" min="0" class="form-control" placeholder="Bid amount" aria-label="bid-amount" aria-describedby="bid-amount" <?php if (!$user_logged_in) {
                                                                                                                                                                              echo "disabled";
                                                                                                                                                                            } ?>>
          </div>
          <button type="submit" class="btn <?php if ($user_logged_in) {
                                              echo "btn-primary";
                                            } else {
                                              echo "btn-secondary";
                                            } ?>" <?php if (!$user_logged_in) {
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