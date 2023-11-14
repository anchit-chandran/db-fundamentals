<?php include_once("utilities.php") ?>
<?php

$finalResult = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $subcategoryId = $_GET["subcategory"];

    
    $productQuery = runQuery("SELECT * 
    FROM Product  
    WHERE subcategoryId = {$subcategoryId};
    ");


// TODO: Fill in remaining time
while ($row = $productQuery->fetch_assoc()) {
        
        $product_id = $row['productId'];
        $current_price = (array_values(runQuery("SELECT MAX(amount) FROM Bid WHERE productId = " . $product_id)->fetch_assoc())[0]);  // Fetch from Bids
        $num_bids = (array_values(runQuery("SELECT COUNT(*) FROM Bid WHERE productId = " . $product_id)->fetch_assoc())[0]);  // Fetch from Bids
        $end_date_str = $row['auctionEndDatetime'];

        $finalResult .= "<tr>
        <th scope='row'>{$row['name']}</th>
        <td>{$row['subcategoryId']}</td>
        <td>{$current_price}</td>
        <td>{$num_bids}</td>
        <td>{$end_date_str}</td>
      </tr>";
    }
}

echo $finalResult;
?>