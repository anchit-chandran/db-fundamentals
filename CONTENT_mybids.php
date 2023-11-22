<?php include_once("CONTENT_header.php") ?>
<?php include_once 'database.php' ?>
<?php include_once("utilities.php") ?>

<div class="row">

    <div class="col">
        <h2 class="my-3">My bids</h2>
        <?php
        // This page is for showing a user the auctions they've bid on.
        // It will be pretty similar to browse.php, except there is no search bar.
        // Check user's credentials (cookie/session).
        // Perform a query to pull up the auctions they've bidded on.
        // Loop through results and print them out as list items.
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $bids = runQuery("SELECT * FROM Bid WHERE userId = {$_SESSION['userId']} ORDER BY bidTime DESC");
            if (mysqli_num_rows($bids) == 0) {
                echo "<p>You have not made any bids yet.</p>";
            } else {
                echo ("<table class='table table-hover'>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Amount</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>");

                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                    while ($row = $bids->fetch_assoc()) {
                        $productName = (array_values(runQuery("SELECT name FROM Product WHERE productId = " . $row['productId'])->fetch_assoc())[0]);
                        $productId = $row['productId'];
                        echo "<tr>
                            <th><a href='listing.php?productId={$productId}'>{$productName}</a></th>
                            <td>{$row['amount']}</td>
                            <td>{$row['bidTime']}</td>
                        </tr>";
                    }
                }
                echo ('
            </tbody>
        </table>');
            }
        }

        ?>
    </div>

</div>