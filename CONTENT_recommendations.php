<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

$userId = $_SESSION['userId'];

$user_bid_categories_result = runQuery("WITH bid_cat AS (
    SELECT bid.userId, product.subcategoryId
    FROM bid
    JOIN product 
        ON bid.productId = product.productId
    WHERE bid.userId = {$userId}
)
SELECT DISTINCT(category.categoryId) AS 'bidItemCategoryId'
FROM bid_cat
JOIN subcategory
	ON bid_cat.subcategoryId = subcategory.subCategoryId
JOIN category
	ON subcategory.categoryId=category.categoryId");

$user_bid_categories = [];
while ($row = mysqli_fetch_assoc($user_bid_categories_result)) {
    $user_bid_categories[] = $row['bidItemCategoryId'];
}

function getProductsForBidCategories($id)
{
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
      AND P.subcategoryId IN (SELECT subCategoryId FROM subcategory WHERE categoryId = {$id})
      AND P.productId NOT IN (SELECT productId FROM bid WHERE userId = {$_SESSION['userId']})
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
        highestBidAmount DESC
        ");
    return $products;
}

function renderProductTableForBidCategories($products)
{

    echo "
            <table class='table table-hover'>
            <thead>
                <tr>
                    <th scope='col'>Name</th>
                    <th scope='col'>Description</th>
                    <th scope='col'>Highest bid <?php echo '⬇️' ?></th>
                    <th scope='col'>Number of bids</th>
                    <th scope='col'>Remaining time</th>
                </tr>
            </thead>
            <tbody>";

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

        echo "<tr> 
                            <th scope='row'><a href='listing.php?productId={$row['productId']}'>{$row['name']}</a></th>
                            <td>{$row['description']}</td>
                            <td>{$highestBidAmount}</td>
                            <td>{$num_bids}</td>
                            <td>{$time_remaining}</td>
                        </tr>";
    }

    echo "</tbody>";
    echo "</table>";
}



?>

<div class="row py-3">

    <div class="col">
        <h2 class="">Recommendations for you</h2>

        <?php

        foreach ($user_bid_categories as $categoryId) {
            $products = getProductsForBidCategories($categoryId);

            $categoryName = array_values(runQuery("SELECT categoryName FROM category WHERE categoryId = {$categoryId}")->fetch_assoc())[0];

            echo "<h4 class='text-muted'>Because you bid on products in the <b>{$categoryName}</b> category:</h4>";

            renderProductTableForBidCategories($products);
        }

        ?>

    </div>

</div>