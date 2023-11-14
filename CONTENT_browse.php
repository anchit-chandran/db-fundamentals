<?php include_once("CONTENT_header.php") ?>
<?php include_once 'database.php' ?>
<?php include_once("utilities.php") ?>

<div class="container">

  <h2 class="my-3">Browse listings</h2>

  <div id="searchSpecs">
    <!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
    <form method="get" action="browse.php">
      <div class="row">
        <div class="col-md-4 pr-0">
          <div class="form-group">
            <label for="keyword" class="cat">Search keyword:</label>
            <div class="input-group">
              <input name='search_term' type="text" class="form-control border-left-0" id="keyword" placeholder="Search for anything">
            </div>
          </div>
        </div>
        <div class="col-md-3 pr-0">
          <div class="form-group">
            <label for="cat" class="mx-2">Search categories:</label>
            <div id="cat-container">
              <!-- name="make" hx-get="/models" hx-target="#models" -->
              <div id='debug'></div>  
              <select name="category-option" class="form-control" id="cat" hx-get="CONTENT_browse_subcategories.php" hx-target="#debug" hx-swap="innerHTML">
              <option selected value="all">All categories</option>
                <option value="fill">Fill me in</option>
                <option value="with">with options</option>
                <option value="populated">populated from a database?</option>
              </select>
            </div>
          </div>
        </div>
        <div class="col-md-3 pr-0">
          <div class="form-group">
            <label for="subcat" class="mx-2">Search subcategories:</label>
            <div id="subcat-container"></div>
          </div>
        </div>
        <div class="col-md-2 pr-0">
          <div class="form-inline">
            <label class="mx-2" for="order_by">Sort by:</label>
            <select class="form-control" id="order_by">
              <option value="pricehigh">Price (highest)</option>
              <option selected value="pricelow">Price (lowest)</option>
              <option value="bidhigh">Bids (highest)</option>
              <option value="bidlow">Bids (lowest)</option>
              <option value="date">Expiry (soonest)</option>
              <option value="date">Expiry (latest)</option>
            </select>
          </div>
        </div>
        <!-- <div class="col-md-1 p-0 d-flex flex-column justify-content-end">
      <button type="submit" class="btn btn-primary btn-block">Search</button>
    </div> -->
      </div>
    </form>
  </div> <!-- end search specs bar -->


</div>

<?php
// Retrieve these from the URL
if (!isset($_GET['keyword'])) {
  // TODO: Define behavior if a keyword has not been specified.
} else {
  $keyword = $_GET['keyword'];
}

if (!isset($_GET['cat'])) {
  // TODO: Define behavior if a category has not been specified.
} else {
  $category = $_GET['cat'];
}

if (!isset($_GET['order_by'])) {
  // TODO: Define behavior if an order_by value has not been specified.
} else {
  $ordering = $_GET['order_by'];
}

if (!isset($_GET['page'])) {
  $curr_page = 1;
} else {
  $curr_page = $_GET['page'];
}

/* TODO: Use above values to construct a query. Use this query to 
     retrieve data from the database. (If there is no form data entered,
     decide on appropriate default value/default query to make. */

/* For the purposes of pagination, it would also be helpful to know the
     total number of results that satisfy the above query */
$num_results = 96; // TODO: Calculate me for real
$results_per_page = 10;
$max_page = ceil($num_results / $results_per_page);
?>

<div class="container mt-5">

  <!-- TODO: If result set is empty, print an informative message. Otherwise... -->

  <ul class="list-group">

    <!-- TODO: Use a while loop to print a list item for each auction listing
     retrieved from the query -->

    <?php

    // Fetch products from Product table
    $getAllProductTable = "SELECT * FROM Product";
    $productTable = runQuery($getAllProductTable);

    if ($productTable) {
      // Loop through each row in the result set
      while ($row = $productTable->fetch_assoc()) {
        $product_id = $row['productId'];
        $title = $row['name'];
        $description = $row['description'];
        $current_price = (array_values(runQuery("SELECT MAX(amount) FROM Bid WHERE productId = " . $product_id)->fetch_assoc())[0]);  // Fetch from Bids
        $num_bids = (array_values(runQuery("SELECT COUNT(*) FROM Bid WHERE productId = " . $product_id)->fetch_assoc())[0]);  // Fetch from Bids
        $end_date_str = $row['auctionEndDatetime'];
        // Excluded fields: $row['auctionStartDatetime'], $row['state'], $row['sellerId'], $row['subcategoryId']
        // This uses a function defined in utilities.php
        print_listing_li($product_id, $title, $description, $current_price, $num_bids, $end_date_str);
      }
    } else {
      echo "Error executing query.";
    }



    $item_id = "516";
    $title = "Different title";
    $description = "Very short description.";
    $current_price = 13.50;
    $num_bids = 3;
    $end_date_str = new DateTime('2020-11-02T00:00:00');

    // print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date_str);
    ?>

  </ul>

  <!-- Pagination for results listings -->
  <nav aria-label="Search results pages" class="mt-5">
    <ul class="pagination justify-content-center">

      <?php

      // Copy any currently-set GET variables to the URL.
      $querystring = "";
      foreach ($_GET as $key => $value) {
        if ($key != "page") {
          $querystring .= "$key=$value&amp;";
        }
      }

      $high_page_boost = max(3 - $curr_page, 0);
      $low_page_boost = max(2 - ($max_page - $curr_page), 0);
      $low_page = max(1, $curr_page - 2 - $low_page_boost);
      $high_page = min($max_page, $curr_page + 2 + $high_page_boost);

      if ($curr_page != 1) {
        echo ('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
      }

      for ($i = $low_page; $i <= $high_page; $i++) {
        if ($i == $curr_page) {
          // Highlight the link
          echo ('
    <li class="page-item active">');
        } else {
          // Non-highlighted link
          echo ('
    <li class="page-item">');
        }

        // Do this in any case
        echo ('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
      }

      if ($curr_page != $max_page) {
        echo ('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
      }
      ?>

    </ul>
  </nav>


</div>