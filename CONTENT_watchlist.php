<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");
?>

<div class="row">

    <div class="col">
        <h2 class="my-3">Your Watchlist</h2>
        <?php


        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $userId = $_SESSION['userId'];
            $number_items = array_values(runQuery("SELECT COUNT(*) FROM WatchItem WHERE userId = {$userId}")->fetch_assoc());

            if (($number_items == null || $number_items[0] == 0)) {
                echo "<p>No items on the watchlist</p>";
            } else {



                $products = runQuery("SELECT p.*, w.watchItemId 
                    FROM WatchItem w 
                    JOIN Product p 
                        ON w.productId = p.productId 
                    WHERE w.userId = {$userId} AND p.auctionEndDatetime > NOW()
                    ORDER BY w.watchItemId DESC"
                );
                $watchlist = array();
                $now = new DateTime();
                while ($row = $products->fetch_assoc()) {
                    $highest_bid_or_NULL = (array_values(runQuery("SELECT MAX(amount) FROM Bid WHERE productId = " . $row['productId'])->fetch_assoc())[0]);
                    $highest_bid = ($highest_bid_or_NULL != NULL) ? $highest_bid_or_NULL : "No Bids!";
                    $number_of_bids_or_NULL = (array_values(runQuery("SELECT COUNT(*) FROM Bid WHERE productId = " . $row['productId'])->fetch_assoc())[0]);
                    $number_of_bids = ($number_of_bids_or_NULL != NULL) ? $number_of_bids_or_NULL : "No Bids!";
                    $subCategory_name = (array_values(runQuery("SELECT subCategoryName FROM SubCategory WHERE subCategoryId = " . $row['subcategoryId'])->fetch_assoc())[0]);
                    $category_id = (array_values(runQuery("SELECT categoryId FROM SubCategory WHERE subCategoryId = " . $row['subcategoryId'])->fetch_assoc())[0]);
                    $category_name = (array_values(runQuery("SELECT categoryName FROM Category WHERE categoryId = " . $category_id)->fetch_assoc())[0]);
                    $end_date_str = $row['auctionEndDatetime'];
                    $end_date = datetime::createFromFormat('Y-m-d H:i:s', $end_date_str);
                    if ($now > $end_date) {
                        $time_to_end = -1;
                        $time_remaining = 'This auction has ended';
                    } else {
                        // Get interval:
                        $time_to_end = date_diff($now, $end_date);
                        $time_remaining = display_time_remaining($time_to_end);
                    }

                    $watchlist[] = array($row['watchItemId'], $row['productId'], $row['name'], $row['description'], $highest_bid, $number_of_bids, $category_name, $subCategory_name, $time_remaining);
                }

        ?>
    </div>



    <div class="table  mt-5">
        <table class="table table-hover" id="watchlist">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</>
                    <th>Highest Bid</th>
                    <th>Number of bids</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Time Remaining</th>
                </tr>
            </thead>
            <tbody name="watchlist-content" id="watchlist-content">
                <?php
                foreach ($watchlist as $row) {
                    $watchItemIdObj = json_encode(array("operation" => "delete", "watchItemId" => $row[0]));
                    $productLink = "listing.php?productId={$row[1]}";
                    echo "<tr data-url='{$productLink}' class='clickable_tr'>
                        <td class='fw-bold'><a href='{$productLink}'>{$row[2]}</a></td>
                        <td>{$row[3]}</td>
                        <td>{$row[4]}</td>
                        <td>{$row[5]}</td>
                        <td>{$row[6]}</td>
                        <td>{$row[7]}</td>
                        <td>{$row[8]}</td>
                        <td><button class='btn btn-danger text-nowrap' hx-confirm='Are you sure you want to remove from watchlist?' hx-post='watchitem_button.php' hx-swap='outerHTML' hx-trigger='click' name='remove-watchitem' hx-vals=$watchItemIdObj id='remove-watchitem'>- Remove from watchlist</button></td>
                    </tr>";
                }
                ?>

                <!-- REMOVE FROM WATCHLIST BUTTON -->

            </tbody>

        </table>
    </div>
<?php }
        } ?>
</div>