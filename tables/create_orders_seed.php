<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Orders Table</h1> <br>";

// Drop table if exists
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 0;");
$dropSql = "DROP TABLE IF EXISTS Orders";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Orders dropped. <br>";
} else {
    echo "Error dropping table. <br>";
}
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 1;");

// create Orders table
$createOrdersTable = "CREATE TABLE Orders (
    orderId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL, 
    state ENUM('Processing', 'Shipped', 'Delivered'),    
    productId INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES User(userId),
    FOREIGN KEY (productId) REFERENCES Product(productId)
) ENGINE=INNODB;

  ";

if (runQuery($createOrdersTable)) {
    echo "Successfully created Orders Table <br>";
} else {
    echo "Error creating Orders table <br>";
}

$seedOrders = "INSERT INTO Orders (state, productId, userId)
    VALUES 
    ('Processing', 1, 1),
    ('Shipped', 2, 2)
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
        echo "Order ID: " . $row['orderId'] . "<br>";
        echo "State: " . $row['state'] . "<br>";
        echo "Product ID: " . $row['productId'] . "<br>";
        echo "User ID: " . $row['userId'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>