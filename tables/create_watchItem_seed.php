<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>WatchItem Table</h1> <br>";

// Drop table if exists
$dropSql = "DROP TABLE IF EXISTS WatchItem";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table WatchItem dropped. <br>";
} else {
    echo "Error dropping table.  <br>";
}

// create WatchItem table
$createWatchItemTable = "CREATE TABLE WatchItem (
      watchItemId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      userId INT NOT NULL,
      productId INT NOT NULL
  );
  ";
if (runQuery($createWatchItemTable)) {
    echo "Successfully created WatchItem Table <br>";
} else {
    echo "Error creating WatchItem table  <br>";
}

$seedWatchItems = "INSERT INTO WatchItem (userId, productId)
    VALUES 
    (1, 1),
    (1, 2);";

if (runQuery($seedWatchItems)) {
    echo "Successfully seeded WatchItems. <br>";
} else {
    echo "Error seeding WatchItems <br>";
}

// Look at WatchItems
$getAllWatchItemTable = "SELECT * FROM WatchItem";
$watchItemTable = runQuery($getAllWatchItemTable);
if ($watchItemTable) {
    // Loop through each row in the result set
    while ($row = $watchItemTable->fetch_assoc()) {
        echo "-----------------------<br>";
        echo "WatchItem ID: " . $row['watchItemId'] . "<br>";
        echo "User ID: " . $row['userId'] . "<br>";
        echo "Product ID: " . $row['productId'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>