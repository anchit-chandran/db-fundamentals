<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Product Table</h1> <br>";

// Drop table if exists
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 0;");
$dropSql = "DROP TABLE IF EXISTS Product";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Product dropped. <br>";
} else {
    echo "Error dropping table. <br>";
}
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 1;");

// create Product table
$createProductTable = "CREATE TABLE Product (
    productId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(500) NOT NULL,
    description VARCHAR(1000) NOT NULL,
    auctionStartDatetime DATETIME NOT NULL,
    auctionEndDatetime DATETIME NOT NULL,
    reservePrice DECIMAL(10,2) DEFAULT 0,
    startPrice DECIMAL(10,2) DEFAULT 0,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    image LONGBLOB,
    state ENUM('Brand New', 'Slightly Used', 'Used'),
    userId INT NOT NULL,
    subcategoryId INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE,
    FOREIGN KEY (subcategoryId) REFERENCES SubCategory(subcategoryId) ON DELETE CASCADE
) ENGINE=INNODB;";

if (runQuery($createProductTable)) {
    echo "Successfully created Product Table <br>";
} else {
    echo "Error creating Product table <br>";
}

$now = new DateTime();
$in_1_min = new DateTime();
$in_1_hr = new DateTime();

// DAYLIGHT SAVINGS SO MINUS 1 HOUR
$now->modify("-1hour");
$in_1_min->modify("+1 minute -1 hour");
$in_1_hr->modify('+1 hour');

$now = $now->format("Y-m-d H:i:s");
$in_1_min = $in_1_min->format("Y-m-d H:i:s");
$in_1_hr = $in_1_hr->format("Y-m-d H:i:s");



$seedProducts = "INSERT INTO Product (name, description, auctionStartDatetime, auctionEndDatetime, state, userId, subcategoryId)
VALUES 
('Tesco Extra Mature Cheddar Cheese, 400g', 'Tasteless lump of rubber', '2016-06-18 10:34:09', '2023-02-23 21:14:54', 'Brand New', 1, 1),
('Sainsbury''s Gouda Cheese, 256g', 'Half eaten', '2021-06-18 10:34:09', '2025-09-24 01:03:55', 'Slightly Used', 2, 1),
('Death Lurk II Team Skateboard Deck - 8', 'The Lurk II deck from Death features a cyclops creature printed down the length of the board with a mushroom growing out of its tongue. Death branding is featured on the tail.', '2022-07-19 10:36:09', '2025-09-14 11:13:55', 'Brand New', 2, 3),
('Moldy Bread From The Sink', 'Try at your peril', '2022-10-03 21:54:13', '{$now}', 'Used', 2, 2),
('Apple', 'This is an apple', '2022-10-03 21:54:13', '{$in_1_min}', 'Brand New', 2, 2),
('orange', 'This is an orange', '2022-10-03 21:54:13', '{$in_1_hr}', 'Brand New', 2, 2),
('Banana', 'This is an banana', '2022-10-03 21:54:13', '2023-12-31 18:08:41', 'Used', 2, 2),
('Lime', 'This is a lime', '2022-10-03 21:54:13', '2023-12-31 18:08:41', 'Used', 2, 2),
('EXPIRED Moldy Bread From The Sink', 'Try at your peril', '2022-10-03 21:54:13', '2024-12-31 18:08:41', 'Used', 2, 2)


;";

if (runQuery($seedProducts)) {
    echo "Successfully seeded Products. <br>";
} else {
    echo "Error seeding Products <br>";
}

// Look at Products
$getAllProductTable = "SELECT * FROM Product";
$productTable = runQuery($getAllProductTable);
if ($productTable) {
    // Loop through each row in the result set
    while ($row = $productTable->fetch_assoc()) {
        echo "-----------------------<br>";
        echo "Product ID: " . $row['productId'] . "<br>";
        echo "Name: " . $row['name'] . "<br>";
        echo "Description: " . $row['description'] . "<br>";
        echo "Auction Start Datetime: " . $row['auctionStartDatetime'] . "<br>";
        echo "Auction End Datetime: " . $row['auctionEndDatetime'] . "<br>";
        
        echo "Reserve Price: " . $row['reservePrice'] . "<br>";
        echo "Start Price: " . $row['startPrice'] . "<br>";
        echo "Created At: " . $row['createdAt'] . "<br>";
        echo "Updated At: " . $row['updatedAt'] . "<br>";
        echo "Image Location: " . $row['image'] . "<br>";
        echo "State: " . $row['state'] . "<br>";
        echo "User (seller) ID: " . $row['userId'] . "<br>";
        echo "Subcategory ID: " . $row['subcategoryId'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>