<?php

include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

$userId = $_GET['userId'];

$userDetails = runQuery("SELECT * FROM User WHERE userId = {$userId}")->fetch_assoc();

$products = runQuery("SELECT
P.productId,
P.name,
P.description,
P.auctionStartDatetime,
P.auctionEndDatetime,
P.reservePrice,
P.startPrice,
P.createdAt,
P.updatedAt,
P.image,
P.state,
P.userId,
P.subcategoryId, 
MAX(B.amount) AS highestBidAmount
FROM Product as P
LEFT JOIN 
    bid AS B ON P.productId = B.productId
WHERE 
  P.auctionEndDatetime > NOW()
  AND P.userId = {$userId}
GROUP BY
    P.productId,
    P.name,
    P.description,
    P.auctionStartDatetime,
    P.auctionEndDatetime,
    P.reservePrice,
    P.startPrice,
    P.createdAt,
    P.updatedAt,
    P.image,
    P.state,
    P.userId,
    P.subcategoryId

  ORDER BY
    highestBidAmount DESC");

?>

<form class="row" method='POST'>
    <div class="col-md-3 border-right">
        <div class="d-flex flex-column align-items-center text-center">

            <img class="rounded-circle mt-5" width="150px" src="https://t3.ftcdn.net/jpg/05/71/08/24/360_F_571082432_Qq45LQGlZsuby0ZGbrd79aUTSQikgcgc.jpg">

            <span class="font-weight-bold">
                <?php echo ucfirst($userDetails['firstName']) ?>
            </span>

        </div>

        <?php
        $avgSellerRating = runQuery(
            "SELECT ROUND(AVG(rating), 2) AS avgRating
            FROM Product P
            JOIN Feedback F
            ON P.productId = F.productId
            WHERE P.userId = {$userId};
            "
        )->fetch_assoc()["avgRating"];
        ?>
        <div class="d-flex flex-column align-items-center text-center mt-2">
            <p class="fw-lighter">Average seller rating: 
                <?php
                    if ($avgSellerRating) {
                        echo $avgSellerRating;
                        echo " / 5";
                    } else {
                        echo "no ratings yet";
                    }
                ?> 
            </p>
            <p class="fw-lighter">
                <?php
                if ($avgSellerRating) {
                    include("CONTENT_rating.php");
                }
                ?>
            </p>
        </div>

    </div>
    <div class="col border-right">
        <div class="p-3 py-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-right">Auctions</h4>
            </div>

            <?php
            if (mysqli_num_rows($products) == 0) {
                echo "<p>This user has not made any auctions yet.</p>";
            } else {
                echo "<table class='table table-hover' id='auction_items_table'>
                        <thead>
                            <tr>
                                <th scope='col'>Auction Image</th>
                                <th scope='col'>Name</th>
                                <th scope='col'>Description</th>
                                <th scope='col'>Highest bid <?php echo '⬇️' ?></th>
                                <th scope='col'>Number of bids</th>
                                <th scope='col'>Remaining time</th>
                            </tr>
                        </thead>
                        <tbody>
                        ";

                while ($row = $products->fetch_assoc()) {

                    // GET N BIDS
                    $num_bids = (array_values(runQuery("SELECT COUNT(*) FROM Bid WHERE productId = " . $row['productId'])->fetch_assoc())[0]);

                    // GET TIME REMAINING
                    $end_date_str = $row['auctionEndDatetime'];
                    $now = new DateTime();
                    $end_date = datetime::createFromFormat('Y-m-d H:i:s', $end_date_str);
                    if ($now > $end_date) {
                        $time_remaining = 'This auction has ended';
                    } else {
                        // Get interval:
                        $time_to_end = date_diff($now, $end_date);
                        $time_remaining = display_time_remaining($time_to_end);
                    }

                    //  RENDER HIGHEST BID AMOUNT
                    if ($row['highestBidAmount'] > 0) {
                        $highestBidAmount = "£{$row['highestBidAmount']}";
                    } else {
                        $highestBidAmount = "No bids!";
                    }

                    if ($row["image"] != null) {
                        $image = $row["image"];
                        $base64image = base64_encode($image);
                        $imageField = "<div class='d-flex align-itemes-center justify-content-center' width='100' height='100'><img src='data:image/jpeg;base64," . $base64image . "' alt='Blob Image' style='object-fit:contain' width='100' height='100'></div>";
                    } else {
                        $imageField = "<p><span class='fw-bold'>No image uploaded with this listing</span></p>";
                    }
                    echo "<tr>
              <td class='col-1'>$imageField</td>
                  <th scope='row'><a href='listing.php?productId={$row['productId']}'>{$row['name']}</a></th>
                  <td>{$row['description']}</td>
                  <td>{$highestBidAmount}</td>
                  <td>{$num_bids}</td>
                  <td>{$time_remaining}</td>
                  </tr>";
                }

                echo "</tbody>
                    </table>";
            }
            ?>

        </div>

    </div>



</form>