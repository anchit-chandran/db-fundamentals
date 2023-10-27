<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>User Table</h1> <br>";

// Drop table if exists
$dropSql = "DROP TABLE IF EXISTS User";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table User dropped. <br>";
} else {
    echo "Error dropping table.  <br>";
}

// create User table
$createUserTable = "CREATE TABLE User (
      userId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      email VARCHAR(254) NOT NULL,
      password CHAR(60) NOT NULL,
      firstName VARCHAR(100) NOT NULL,
      lastName VARCHAR(100) NOT NULL,
      isActive BOOL DEFAULT FALSE,
      isSuperuser BOOL DEFAULT FALSE
  );
  ";
if (runQuery($createUserTable)) {
    echo "Successfully created User Table <br>";
} else {
    echo "Error creating User table  <br>";
}

$seedUsers = "INSERT INTO User (email, password, firstName, lastName, isActive, isSuperuser) 
    VALUES 
    ('user1@example.com', 'hashedpassword1', 'John', 'Doe', TRUE, FALSE),
    ('user2@example.com', 'hashedpassword2', 'Jane', 'Smith', TRUE, FALSE),
    ('superuser@example.com', 'hashedpassword3', 'Alice', 'Johnson', FALSE, TRUE);";

if (runQuery($seedUsers)) {
    echo "Successfully seeded Users. <br>";
} else {
    echo "Error seeding Users <br>";
}

// Look at Users
$getAllUserTable = "SELECT * FROM User";
$userTable = runQuery($getAllUserTable);
if ($userTable) {
    // Loop through each row in the result set
    while ($row = $userTable->fetch_assoc()) {
        echo "-----------------------<br>";
        echo "User ID: " . $row['userId'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
        echo "First Name: " . $row['firstName'] . "<br>";
        echo "Last Name: " . $row['lastName'] . "<br>";
        echo "Is Active: " . ($row['isActive'] ? 'Yes' : 'No') . "<br>";
        echo "Is Superuser: " . ($row['isSuperuser'] ? 'Yes' : 'No') . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>