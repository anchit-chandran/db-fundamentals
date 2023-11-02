<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Address Table</h1> <br>";

// Drop table if exists
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 0;");
$dropSql = "DROP TABLE IF EXISTS Address";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Address dropped. <br>";
} else {
    echo "Error dropping table. <br>";
}
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 1;");

// create Address table
$createAddressTable = "CREATE TABLE Address (
      addressId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      phoneNumber VARCHAR(20) NOT NULL,
      address_1 VARCHAR(50) NOT NULL,
      address_2 VARCHAR(50),
      address_3 VARCHAR(50),
      city VARCHAR(189) NOT NULL,
      country VARCHAR(90) NOT NULL,
      zipCode VARCHAR(20) NOT NULL,
      userId INT NOT NULL,
      FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE
  ) ENGINE=INNODB;
  ";
if (runQuery($createAddressTable)) {
    echo "Successfully created Address Table <br>";
} else {
    echo "Error creating Address table <br>";
}

$seedaddress = "INSERT INTO Address (phoneNumber, address_1, city, country, zipCode, userId)
    VALUES 
    ('+44(0)2080590939', 'UCL', 'London', 'United Kingdom', 'WC1E 6BT', 1),
    ('+44(0)2070257184', 'M&M\'S London, 1 Swiss Ct', 'London', 'United Kingdom', 'W1D 6AP', 3),
    ('+44(0)1234567890', '124-125 Tottenham Ct Rd', 'London', 'United Kingdom', 'W1T 5AS', 2);";

if (runQuery($seedaddress)) {
    echo "Successfully seeded address. <br>";
} else {
    echo "Error seeding address <br>";
}

// Look at Address
$getAllAddressTable = "SELECT * FROM Address";
$addressTable = runQuery($getAllAddressTable);
if ($addressTable) {
    // Loop through each row in the result set
    while ($row = $addressTable->fetch_assoc()) {
        echo "-----------------------<br>";
        echo "Address ID: " . $row['addressId'] . "<br>";
        echo "Phone Number: " . $row['phoneNumber'] . "<br>";
        echo "Address 1: " . $row['address_1'] . "<br>";
        echo "Address 2: " . $row['address_2'] . "<br>";
        echo "Address 3: " . $row['address_3'] . "<br>";
        echo "City: " . $row['city'] . "<br>";
        echo "country: " . $row['country'] . "<br>";
        echo "Zip Code: " . $row['zipCode'] . "<br>";
        echo "User ID: " . $row['userId'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>