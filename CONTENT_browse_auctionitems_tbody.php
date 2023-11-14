<?php include_once("utilities.php") ?>
<?php

$finalResult = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $subcategoryId = $_GET["subcategory"];

    $productQuery = runQuery("SELECT * 
    FROM Product  
    WHERE subcategoryId = {$subcategoryId};
    ");

    // TODO: Fill in max bid price
    // TODO: Fill in num bids (bid table)
    // TODO: Fill in remaining time
    while ($row = $productQuery->fetch_assoc()) {

        $finalResult .= "<tr>
        <th scope='row'>name</th>
        <td>$row['subCategoryId']</td>
        <td>£xxxx</td>
        <td>33</td>
        <td>24hrs</td>
      </tr>
      
      ";
    }
}

echo $finalResult;
?>