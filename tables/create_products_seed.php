<?php

// Contains db connection code
include_once 'database.php';

// Drop table if exists
$dropSql = "DROP TABLE IF EXISTS Product";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Product dropped. ";
} else {
    echo "Error dropping table. ";
}

// create Product table
$createProductTable = "CREATE TABLE Product (
    productId INT NOT NULL AUTO_INCREMENT,
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
    sellerId INT NOT NULL,
    subcategoryId INT NOT NULL,
    PRIMARY KEY (productId)
);

  ";
if (runQuery($createProductTable)) {
    echo "Successfully created Product Table";
} else {
    echo "Error creating Product table ";
}

$seedProducts = "INSERT INTO Product (name, description, auctionStartDatetime, auctionEndDatetime, state, sellerId, subcategoryId)
    VALUES 
    ('Tesco Extra Mature Cheddar Cheese, 400g', 'Tasteless lump of rubber', '2016-06-18 10:34:09', '2026-02-23 21:14:54', 'Brand New', 1, 1),
    ('Sainsbury\'s Gouda Cheese, 256g', 'Half eaten', '2021-06-18 10:34:09', '2022-09-24 01:03:55', 'Slightly Used', 1, 1),
    ('Cheese Slice From The Sink', 'Try at your peril', '2022-10-03 21:54:13', '2026-01-31 18:08:41', 'Used', 1, 1);";

if (runQuery($seedProducts)) {
    echo "Successfully seeded Products.";
} else {
    echo "Error seeding Products ";
}

// Look at Products
echo "<h1>Product Table</h1> <br>";
$getAllProductTable = "SELECT * FROM Product";
$productTable = runQuery($getAllProductTable);
if ($productTable) {
    // Loop through each row in the result set
    while ($row = $productTable->fetch_assoc()) {
        echo "Product ID: " . $row['productId'] . "<br>";
        echo "Name: " . $row['name'] . "<br>";
        echo "Description: " . $row['description'] . "<br>";
        echo "Auction Start Datetime: " . $row['auctionStartDatetime'] . "<br>";
        echo "Auction End Datetime: " . $row['auctionEndDatetime'] . "<br>";
        echo "State: " . $row['state'] . "<br>";
        echo "Seller ID: " . $row['sellerId'] . "<br>";
        echo "Subcategory ID: " . $row['subcategoryId'] . "<br>";
        echo "-----------------------<br>";
    }
} else {
    echo "Error executing query.";
}

?>