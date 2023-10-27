<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Orders Table</h1> <br>";

// Drop table if exists
$dropSql = "DROP TABLE IF EXISTS Orders";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Orders dropped. <br>";
} else {
    echo "Error dropping table. <br>";
}

// create Orders table
$createOrdersTable = "CREATE TABLE Orders (
    orderId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    paymentMethod VARCHAR(500) NOT NULL,
    state ENUM('Processing', 'Shipped', 'Delivered'),    
    productId INT NOT NULL,
    userId INT NOT NULL,
    addressId INT NOT NULL
);

  ";

if (runQuery($createOrdersTable)) {
    echo "Successfully created Orders Table <br>";
} else {
    echo "Error creating Orders table <br>";
}

$seedOrders = "INSERT INTO Orders (paymentMethod, state, productId, userId, addressId)
    VALUES 
    ('card XXXX XXXX XXXX XXXX', 'Processing', 1, 1, 1),
    ('paypal XXXXXXXX', 'Shipped', 1, 2, 3)
    ;";

if (runQuery($seedOrders)) {
    echo "Successfully seeded Orders. <br>";
} else {
    echo "Error seeding Orders <br>";
}

// Look at Orders
$getAllOrderTable = "SELECT * FROM Orders";
$orderTable = runQuery($getAllOrderTable);
if ($orderTable) {
    // Loop through each row in the result set
    while ($row = $orderTable->fetch_assoc()) {
        echo "-----------------------<br>";
        echo "Orders ID: " . $row['orderId'] . "<br>";
        echo "Payment Method: " . $row['paymentMethod'] . "<br>";
        echo "State: " . $row['state'] . "<br>";
        echo "Product ID: " . $row['productId'] . "<br>";
        echo "User ID: " . $row['userId'] . "<br>";
        echo "Address ID: " . $row['addressId'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>