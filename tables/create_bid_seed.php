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
    FOREIGN KEY (productId) REFERENCES Product(productId),
    FOREIGN KEY (userId) REFERENCES User(userId)
) ENGINE=INNODB;";

if (runQuery($createBidTable)) {
    echo "Successfully created Bid Table <br>";
} else {
    echo "Error creating Bid table <br>";
}

$seedBids = "INSERT INTO Bid (amount, bidTime, productId, userId)
    VALUES 
    (123.45, '2023-06-18 10:34:09', 1, 1),
    (900.00, '2022-02-10 10:08:41', 2, 1),
    (100.99, '2022-09-30 18:44:02', 1, 2);";

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
        echo "Amount: " . $row['amount'] . "<br>";
        echo "Bid Time: " . $row['bidTime'] . "<br>";
        echo "User ID: " . $row['userId'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>