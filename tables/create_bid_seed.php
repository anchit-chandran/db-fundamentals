<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Bid Table</h1> <br>";

// Drop table if exists
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 0;");
$dropSql = "DROP TABLE IF EXISTS Bid";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Bid dropped. <br>";
} else {
    echo "Error dropping table. <br>";
}
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 1;");

// create Bid table
$createBidTable = "CREATE TABLE Bid (
    bidId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10,2) NOT NULL,
    bidTime DATETIME DEFAULT CURRENT_TIMESTAMP,
    productId INT NOT NULL,
    userId INT NOT NULL,
    FOREIGN KEY (productId) REFERENCES Product(productId) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE
) ENGINE=INNODB;";

if (runQuery($createBidTable)) {
    echo "Successfully created Bid Table <br>";
} else {
    echo "Error creating Bid table <br>";
}

$seedBids = "INSERT INTO Bid (amount, bidTime, productId, userId)
    VALUES 
    (123.45, '2023-01-18 10:34:09', 1, 1),
    (10.00, '2021-06-30 23:47:02', 2, 5),
    (10.99, '2021-07-30 08:34:01', 2, 1),
    (11.50, '2022-07-30 08:44:22', 2, 5),
    (11.60, '2022-08-27 18:44:37', 2, 3),
    (300.00, '2022-11-10 10:07:40', 4, 5),
    (600.00, '2022-11-10 10:08:01', 4, 4),
    (600.00, '2022-11-10 10:08:01', 5, 4),
    (102.23, '2022-11-11 10:09:41', 9, 5),
    (150.53, '2022-11-11 10:09:42', 9, 1),
    (109.00, '2022-11-11 10:09:43', 9, 5),
    
    (1.29, '2022-11-11 10:09:46', 8, 4),

    (900.00, '2022-11-11 10:11:41', 4, 5);";

if (runQuery($seedBids)) {
    echo "Successfully seeded Bids. <br>";
} else {
    echo "Error seeding Bids <br>";
}

// Look at Bids
$getAllBidTable = "SELECT * FROM Bid";
$bidTable = runQuery($getAllBidTable);
if ($bidTable) {
    // Loop through each row in the result set
    while ($row = $bidTable->fetch_assoc()) {
        echo "-----------------------<br>";
        echo "Bid ID: " . $row['bidId'] . "<br>";
        echo "Product ID: " . $row['productId'] . "<br>";
        echo "Amount: " . $row['amount'] . "<br>";
        echo "Bid Time: " . $row['bidTime'] . "<br>";
        echo "User ID: " . $row['userId'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>