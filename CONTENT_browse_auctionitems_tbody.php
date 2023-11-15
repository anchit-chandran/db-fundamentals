<?php include_once("utilities.php") ?>
<?php

$finalResult = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $subcategoryId = $_GET["subcategory"];
    
    $productQueryStr = "SELECT * FROM Product ";

    if ($subcategoryId != -1) {
        $productQueryStr .= "WHERE subcategoryId = {$subcategoryId}";
    }

    $productQuery = runQuery($productQueryStr);


// TODO: Fill in remaining time
    

while ($row = $productQuery->fetch_assoc()) {
        
        $product_id = $row['productId'];
        $current_price = (array_values(runQuery("SELECT MAX(amount) FROM Bid WHERE productId = " . $product_id)->fetch_assoc())[0]);  // Fetch from Bids
        $num_bids = (array_values(runQuery("SELECT COUNT(*) FROM Bid WHERE productId = " . $product_id)->fetch_assoc())[0]);  // Fetch from Bids
        $end_date_str = $row['auctionEndDatetime'];

        $now = new DateTime();
        $end_date = datetime::createFromFormat('Y-m-d H:i:s', $end_date_str);
        if ($now > $end_date) {
            $time_remaining = 'This auction has ended';
        }
        else {
            // Get interval:
            $time_to_end = date_diff($now, $end_date);
            $time_remaining = display_time_remaining($time_to_end);
        }

        $finalResult .= "<tr>
        <th scope='row'>{$row['name']}</th>
        <td>{$row['subcategoryId']}</td>
        <td>{$current_price}</td>
        <td>{$num_bids}</td>
        <td>{$time_remaining}</td>
      </tr>";
    }
}

echo $finalResult;
?>