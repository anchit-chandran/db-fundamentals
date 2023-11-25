<div class="row">

    <div class="col">
        <h2 class="my-3">My listings</h2>
        <?php
        // This page is for showing a user the auction listings they've made.
        // It will be pretty similar to browse.php, except there is no search bar.
        // This can be started after browse.php is working with a database.
        // Feel free to extract out useful functions from browse.php and put them in
        // the shared "utilities.php" where they can be shared by multiple files.
        // TODO: Check user's credentials (cookie/session).
        // TODO: Perform a query to pull up their auctions.
        // TODO: Loop through results and print them out as list items.
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            $products = runQuery("SELECT * FROM Product WHERE userId = {$_SESSION['userId']} ORDER BY updatedAt DESC");
            if (mysqli_num_rows($products) == 0) {
                echo "<p>You have not made any listings yet.</p>";
            } else {
                echo ("<table class='table table-hover'>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Start Price</th>
                    <th>Reserve Price</th>
                    <th>Max Bid</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>");

                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                    while ($row = $products->fetch_assoc()) {
                        $highest_bid_or_NULL = (array_values(runQuery("SELECT MAX(amount) FROM Bid WHERE productId = " . $row['productId'])->fetch_assoc())[0]);
                        $highest_bid = ($highest_bid_or_NULL != NULL) ? $highest_bid_or_NULL : "-";
                        $subCategory_name = (array_values(runQuery("SELECT subCategoryName FROM SubCategory WHERE subCategoryId = " . $row['subcategoryId'])->fetch_assoc())[0]);
                        $category_id = (array_values(runQuery("SELECT categoryId FROM SubCategory WHERE subCategoryId = " . $row['subcategoryId'])->fetch_assoc())[0]);
                        $category_name = (array_values(runQuery("SELECT categoryName FROM Category WHERE categoryId = " . $category_id)->fetch_assoc())[0]);
                        $productLink = "listing.php?productId={$row['productId']}";
                        echo "<tr data-url='{$productLink}' class='clickable_tr'>
                            <th><a href='{$productLink}'>{$row['name']}</a></th>
                            <td>{$row['startPrice']}</td>
                            <td>{$row['reservePrice']}</td>
                            <td>{$highest_bid}</td>
                            <td>{$category_name}</td>
                            <td>{$subCategory_name}</td>
                            <td>{$row['createdAt']}</td>
                            <td>{$row['updatedAt']}</td>
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