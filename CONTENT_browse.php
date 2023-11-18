<?php include_once("CONTENT_header.php") ?>
<?php include_once 'database.php' ?>
<?php include_once("utilities.php") ?>

<?php
$categories = runQuery("SELECT * FROM Category");

// GET INITIAL PRODUCTS

$product_table_query = "SELECT
    P.productId,
    P.name,
    P.description,
    P.auctionStartDatetime,
    P.auctionEndDatetime,
    P.reservePrice,
    P.startPrice,
    P.createdAt,
    P.updatedAt,
    P.image,
    P.state,
    P.userId,
    P.subcategoryId, 
    MAX(B.amount) AS highestBidAmount
    FROM Product as P
    LEFT JOIN 
        bid AS B ON P.productId = B.productId
    WHERE 
      P.auctionEndDatetime > NOW()
    GROUP BY
        P.productId,
        P.name,
        P.description,
        P.auctionStartDatetime,
        P.auctionEndDatetime,
        P.reservePrice,
        P.startPrice,
        P.createdAt,
        P.updatedAt,
        P.image,
        P.state,
        P.userId,
        P.subcategoryId
    
      ORDER BY
        highestBidAmount DESC
        ";
$product_table_query_first_page = $product_table_query . "LIMIT 5";

$all_products = runQuery($product_table_query);
$products_first_page = runQuery($product_table_query_first_page);

// SET INITIAL PAGINATION
$products_per_page = 5;
$n_products = $all_products->num_rows;
$n_pages = ceil($n_products / $products_per_page);

?>

<div class="container">

  <h2 class="my-3">Browse listings</h2>

</div>

<div id="searchSpecs" class='container'>
  <!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
  <form id="filter-form" hx-get='partials/filter_product_table.php' hx-target='#auction_items_table' hx-swap='innerHTML'>
    <div class="row">
      <div class="col-md-4 pr-0">
        <div class="form-group">
          <label for="keyword" class="cat">Search keyword:</label>
          <div class="input-group">
            <input name='search_term' type="text" class="form-control border-left-0" id="keyword" placeholder="Search for anything" hx-trigger="keyup delay:50ms" hx-target="#auction_items_tbody">
          </div>
        </div>
      </div>
      <div class="col-md-3 pr-0">
        <div class="form-group">
          <label for="cat" class="mx-2">Search categories:</label>
          <div id="cat-container">
            <select name="category-option" class="form-control" id="cat" hx-get="partials/get_subcategories.php" hx-trigger="change" hx-target="#subcat" hx-swap='outerHTML'>
              <option selected value="all">All</option>
              <?php while ($row = $categories->fetch_assoc()) {
                echo "<option value=" . "{$row['categoryId']}" . ">{$row['categoryName']}</option>";
              } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-3 pr-0">
        <div class="form-group">
          <label for="subcat" class="mx-2">Search subcategories:</label>
          <select name="subcat-option" class="form-control" id='subcat' disabled>
            <option selected value="all">-</option>
          </select>
        </div>
      </div>
      <div class="col-md-2 pr-0">
        <div class="form-inline">
          <label class="mx-2" for="order_by">Sort by:</label>
          <select name="sort-option" class="form-control" id="order_by">
            <option value="amount-DESC">Price (highest)</option>
            <option value="amount-ASC">Price (lowest)</option>
            <option value="date-ASC">Expiry (soonest)</option>
            <option value="date-DESC">Expiry (latest)</option>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col pt-3">
        <button class="btn btn-primary" type='submit'>Search</button>
      </div>
    </div>
  </form>
</div> <!-- end search specs bar -->


</div>

<div class="container mt-5">
  <table class="table table-hover" id='auction_items_table'>
    <thead>
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Description</th>
        <th scope="col">Highest bid <?php echo '⬇️' ?></th>
        <th scope="col">Number of bids</th>
        <th scope="col">Remaining time</th>
      </tr>
    </thead>
    <tbody>
      <?php
      while ($row = $products_first_page->fetch_assoc()) {

        // GET N BIDS
        $num_bids = (array_values(runQuery("SELECT COUNT(*) FROM Bid WHERE productId = " . $row['productId'])->fetch_assoc())[0]);

        // GET TIME REMAINING
        $end_date_str = $row['auctionEndDatetime'];
        $now = new DateTime();
        $end_date = datetime::createFromFormat('Y-m-d H:i:s', $end_date_str);
        if ($now > $end_date) {
          $time_remaining = 'This auction has ended';
        } else {
          // Get interval:
          $time_to_end = date_diff($now, $end_date);
          $time_remaining = display_time_remaining($time_to_end);
        }

        //  RENDER HIGHEST BID AMOUNT
        if ($row['highestBidAmount'] > 0) {
          $highestBidAmount = "£{$row['highestBidAmount']}";
        } else {
          $highestBidAmount = "No bids!";
        }

        echo "<tr> 
          <th scope='row'><a href='listing.php?productId={$row['productId']}'>{$row['name']}</a></th>
          <td>{$row['description']}</td>
          <td>{$highestBidAmount}</td>
          <td>{$num_bids}</td>
          <td>{$time_remaining}</td>
          </tr>";
      }
      ?>
    </tbody>
  </table>
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <?php
      for ($i = 1; $i <= $n_pages; $i++) {
        echo "<li class='page-item' 
        hx-get='partials/filter_product_table.php?page={$i}'
        hx-target='#auction_items_table'
        hx-include='#filter-form' 
        hx-trigger='click'
        ><a class='page-link' href='#'>{$i}</a></li>";
      }
      ?>
    </ul>
  </nav>
</div>


</div>