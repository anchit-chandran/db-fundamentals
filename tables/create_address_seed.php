<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Address Table</h1> <br>";

// Drop table if exists
$dropSql = "DROP TABLE IF EXISTS Address";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Address dropped. <br>";
} else {
    echo "Error dropping table. <br>";
}

// create Address table
$createAddressTable = "CREATE TABLE Address (
      addressId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      phoneNumber VARCHAR(20) NOT NULL,
      zipCode VARCHAR(20) NOT NULL,
      county VARCHAR(90) NOT NULL,
      city VARCHAR(189) NOT NULL,
      address_3 VARCHAR(50),
      address_2 VARCHAR(50),
      address_1 VARCHAR(50) NOT NULL
  );
  ";
if (runQuery($createAddressTable)) {
    echo "Successfully created Address Table <br>";
} else {
    echo "Error creating Address table <br>";
}

$seedaddress = "INSERT INTO Address (phoneNumber, zipCode, county, city, address_1)
    VALUES 
    ('+44(0)2080590939', 'WC1E 6BT', 'Greater London', 'London', 'UCL'),
    ('+44(0)1234567890', 'W1T 5AS', 'Greater London', 'London', '124-125 Tottenham Ct Rd');";

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
        echo "County: " . $row['county'] . "<br>";
        echo "Zip Code: " . $row['zipCode'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>