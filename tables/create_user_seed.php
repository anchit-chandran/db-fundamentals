<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>User Table</h1> <br>";

// Drop table if exists
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 0;");
$dropSql = "DROP TABLE IF EXISTS User";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table User dropped. <br>";
} else {
    echo "Error dropping table.  <br>";
}
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 1;");

// create User table
$createUserTable = "CREATE TABLE User (
      userId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      email VARCHAR(254) NOT NULL,
      password CHAR(60) NOT NULL,
      firstName VARCHAR(100) NOT NULL,
      lastName VARCHAR(100) NOT NULL,
      isActive BOOL DEFAULT FALSE,
      isSuperuser BOOL DEFAULT FALSE
  ) ENGINE=INNODB;";
  
if (runQuery($createUserTable)) {
    echo "Successfully created User Table <br>";
} else {
    echo "Error creating User table  <br>";
}

// HASHED PW
$hashedPass = password_hash("pw", PASSWORD_DEFAULT);

$seedUsers = "INSERT INTO User (email, password, firstName, lastName, isActive, isSuperuser) 
    VALUES 
    ('user1@example.com', '{$hashedPass}', 'John', 'Doe', TRUE, FALSE),
    ('user2@example.com', '{$hashedPass}', 'Jane', 'Smith', TRUE, FALSE),
    ('user3@example.com', '{$hashedPass}', 'Saul', 'Goodman', TRUE, FALSE),
    ('anchit97123@gmail.com', '{$hashedPass}', 'gmail', 'Chand', TRUE, FALSE),
    ('ucaba71@ucl.ac.uk', '{$hashedPass}', 'ucl', 'Chand', TRUE, FALSE),
    ('superuser@example.com', '{$hashedPass}', 'Alice', 'Johnson', True, TRUE),
    ('anchit96@live.co.uk', '{$hashedPass}', 'Darth', 'Vader', True, TRUE),
    ;";

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
        echo "Password: {$row['password']}<br>";
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