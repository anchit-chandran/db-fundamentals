<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Category Table</h1> <br>";

// Drop table if exists
$dropSql = "DROP TABLE IF EXISTS Category";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Category dropped. <br>";
} else {
    echo "Error dropping table.  <br>";
}

// create Category table
$createCategoryTable = "CREATE TABLE Category (
      categoryId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      categoryName VARCHAR(100)
  );
  ";
if (runQuery($createCategoryTable)) {
    echo "Successfully created Category Table <br>";
} else {
    echo "Error creating Category table  <br>";
}

$seedCategorys = "INSERT INTO Category (categoryName)
    VALUES 
    ('Food'),
    ('Not Food');";

if (runQuery($seedCategorys)) {
    echo "Successfully seeded Categorys. <br>";
} else {
    echo "Error seeding Categorys <br>";
}

// Look at Categorys
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