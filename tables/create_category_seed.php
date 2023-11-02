<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Category Table</h1> <br>";

// Drop table if exists
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 0;");
$dropSql = "DROP TABLE IF EXISTS Category";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Category dropped. <br>";
} else {
    echo "Error dropping table.  <br>";
}
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 1;");

// create Category table
$createCategoryTable = "CREATE TABLE Category (
      categoryId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      categoryName VARCHAR(100)
  ) ENGINE=InnoDB;
  ";
if (runQuery($createCategoryTable)) {
    echo "Successfully created Category Table <br>";
} else {
    echo "Error creating Category table  <br>";
}

$seedCategories = "INSERT INTO Category (categoryName)
    VALUES 
    ('Food'),
    ('Not Food');";

if (runQuery($seedCategories)) {
    echo "Successfully seeded Categories. <br>";
} else {
    echo "Error seeding Categories <br>";
}

// Look at Categories
$getAllCategoryTable = "SELECT * FROM Category";
$categoryTable = runQuery($getAllCategoryTable);
if ($categoryTable) {
    // Loop through each row in the result set
    while ($row = $categoryTable->fetch_assoc()) {
        echo "-----------------------<br>";
        echo "Category ID: " . $row['categoryId'] . "<br>";
        echo "Category Name: " . $row['categoryName'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>