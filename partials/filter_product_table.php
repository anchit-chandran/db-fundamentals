<?php
include_once("../database.php");
include_once("../utilities.php");

// GET PARAMETERS: [search_term] => [category-option] => 1 [subcategory] => 1 [sort-option] => bidlow
if ($_SERVER['REQUEST_METHOD'] == "GET") {


    // SORT ON BIDS
    if ($_GET["sort-option"]) {
        $sort_options = explode('-', $_GET["sort-option"]);

        if ($sort_options[1] == "ASC") {
            $sort_up = True;
        } else {
            $sort_up = False;
        }

        if ($sort_options[0] == "amount") {
            $order_by = "highestBidAmount {$sort_options[1]}";
            $time_sort_icon = "";
            if ($sort_up) {
                $amount_sort_icon = "⬆️";
            } else {
                $amount_sort_icon = "⬇️";
            }
        } else {
            $order_by = "P.auctionEndDatetime {$sort_options[1]}";
            $amount_sort_icon = "";
            if ($sort_up) {
                $time_sort_icon = "⬇️";
            } else {
                $time_sort_icon = "⬆️";
            }
        }
    }

    // GO THROUGH GET PARAMETERS, APPENDING CONDITIONS TO SQL QUERY
    $where_conditions = array();

    if ($_GET["search_term"]) {
        $search_term = $_GET["search_term"];
        $where_conditions[] = "name like '%{$search_term}%'";
    }

    if (isset($_GET["subcategory"])) {
        $subcategoryId = $_GET["subcategory"];
        $where_conditions[] = "subcategoryID = {$subcategoryId}";
    }

    if ($where_conditions) {
        $where_clause = "WHERE " . implode(' AND ', $where_conditions) . " ";
    } else {
        $where_clause = '';
    }

    $product_table_query = "SELECT
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
    {$where_clause}
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
        {$order_by}";


    // ADD PAGINATION
    if (isset($_GET['page']) and $_GET['page'] != 1) {
        $requested_page = $_GET['page'];
        $offset = $requested_page + 1;
    } else {
        $requested_page = 1;
        $offset = 1;
    }

    $products_per_page = 2;

    $product_table_query .= " LIMIT {$products_per_page} ";
    $product_table_query .= " OFFSET {$offset} ";

    echo $product_table_query;

    // FINALLY RUN QUERY
    $filtered_products = runQuery($product_table_query);

    // RENDER HEAD OF TABLE
    echo "<thead>
    <tr>
      <th scope='col'>Name</th>
      <th scope='col'>Description</th>
      <th scope='col'>Highest Bid {$amount_sort_icon}</th>
      <th scope='col'>Number of bids</th>
      <th scope='col'>Remaining time {$time_sort_icon}</th>
    </tr>
  </thead>";

    echo "<tbody>";

    while ($row = $filtered_products->fetch_assoc()) {

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
        <th scope='row'><a href='#'>{$row['name']}</a></th>
        <td>{$row['description']}</td>
        <td>{$highestBidAmount}</td>
        <td>{$num_bids}</td>
        <td>{$time_remaining}</td>
        </tr>";
    }




    echo "</tbody>";
}
