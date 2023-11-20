<?php

include_once 'database.php';

// TODO: handle these errors:
// product_id ie auction missing
// product_id ie auction is not found
// auction not yet ended
// user not logged in
// user not match highest bidder

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['product_id'])) {
        echo "Error: product_id missing ";
    } else {
        $product_id = $_GET['product_id'];        
        $query = "SELECT * FROM Product WHERE productId = {$product_id}";
        $result = runQuery($query)->fetch_assoc();
        if ($result == null) {
            echo "Error: product_id not found ";
        } else {
            $end_date_str = $result['auctionEndDatetime'];
            $now = new DateTime();
            $end_date = datetime::createFromFormat('Y-m-d H:i:s', $end_date_str);
            if ($now > $end_date) {
                echo "Error: auction has ended ";
            }
        }
    }
}

$user_logged_in = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true);
if (!$user_logged_in) {
    echo "Error: user not logged in";
} else {
    $userId = $_SESSION["userId"];
}
?>

<div class="container">

    <!-- Create auction form -->
    <div style="max-width: 800px; margin: 10px auto">
        <h2 class="my-3">Create Feedback</h2>
        <div class="card">
            <div class="card-body">
                <form method="post" action="create_feedback.php" id="createFeedbackForm" enctype="multipart/form-data">
                    <label for="feedback-message"" class=" col-sm col-form-label text-right">Feedback Message for <?php 1 ?></label>
                    <textarea name="feedback-message" class="form-control" id="exampleFormControlTextarea1" rows="6"></textarea>
                    <button type="submit" class="btn btn-primary form-control">Send Feedback</button>
                </form>

                <!-- <form method="post" action="create_auction_result.php" id="createAuctionForm" enctype="multipart/form-data">
        <div class="form-group row">
          <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Title of auction</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="auctionTitle" name="auctionTitle" placeholder="e.g. Black mountain bike">
            <small id="titleHelp" class="form-text text-muted"><span class="text-danger" id="titleDanger">* Required.</span> Title of the item you're selling, which will display in listings. Max: 500 characters</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionDetails" class="col-sm-2 col-form-label text-right">Details</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="auctionDetails" name="auctionDetails" rows="4"></textarea>
            <small id="detailsHelp" class="form-text text-muted"><span class="text-danger" id="detailsDanger">* Required.</span> A short description of the item you're selling, which will display in listings. Max: 1000 characters</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionCategory" class="col-sm-2 col-form-label text-right">Category</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionCategory" name="auctionCategory">
            <option value="">Choose...</option>
            <?php
            foreach ($categories as $category) {
                echo "<option value=\"{$category['categoryId']}\">{$category['categoryName']}</option>";
            }
            ?>
            </select>
            <small id="categoryHelp" class="form-text text-muted"><span class="text-danger" id="categoryDanger">* Required.</span> Select a category for this item.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionSubCategory" class="col-sm-2 col-form-label text-right">Subcategory</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionSubCategory" name="auctionSubCategory">
              
              <script>
                const categoryChoice = document.getElementById("auctionCategory");
                categoryChoice.addEventListener("change", () => {
                  const selectedCategory = categoryChoice.value;
                  fetchSubcategory(selectedCategory);
                })
                function fetchSubcategory(categoryId) {
                  const subcategoryChoice = document.getElementById("auctionSubCategory");
                  subcategoryChoice.innerHTML = "";
                  if (categoryId !== "") {
                    
                    var subcategories = <?php echo json_encode($subcategories); ?>;
                    for (var i = 0; i < subcategories.length; i++) {
                          if (subcategories[i].categoryId === categoryId) {
                            var option = document.createElement("option");
                            option.text = subcategories[i].subCategoryName;
                            option.value = subcategories[i].subCategoryId;
                            subcategoryChoice.add(option)
                          }
                          
                        }

                  } 
                    else {
                        var option = document.createElement("option");
                          option.text = "Choose category first";
                          option.value = "";
                          subcategoryChoice.add(option);
                  }
                }
                fetchSubcategory("")
              </script>
            </select>
            <small class="form-text text-muted"></small>
            <small id="subcategoryHelp" class="form-text text-muted">Select a subcategory for this item.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionCondition" class="col-sm-2 col-form-label text-right">Item Condition</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionCondition" name="auctionCondition">
              <option value="">Choose...</option>
              <option value="Brand New">Brand New</option>
              <option value="Slightly Used">Slightly Used</option>
              <option value="Used">Used</option>
            </select>
            <small id="conditionHelp" class="form-text text-muted"><span class="text-danger" id="conditionDanger">* Required.</span> Select a condition for this item.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionImage" class="col-sm-2 col-form-label text-right">Item Image</label>
          <div class="col-sm-10">
          <input type="file" id="auctionImage" name="auctionImage" accept=".jpg, .jpeg, .png,">
          <button type="button" id="removeImage" class="d-none" onclick="clearImage()">Remove</button>
          <small id="imageHelp" class="form-text text-muted">Upload image for this listing (.jpg, .jpeg, .png) max: 5 MB</small>
          </div>
          
          
        </div>
        <div class="form-group row">
          <label for="auctionStartPrice" class="col-sm-2 col-form-label text-right">Starting price</label>
          <div class="col-sm-10">
	        <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" id="auctionStartPrice" name="auctionStartPrice" step="any">
            </div>
            <small id="startBidHelp" class="form-text text-muted"><span class="text-danger" id="startPriceDanger">* Required.</span> Initial bid amount. Amount must be a valid number and greater than or equal to 0</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionReservePrice" class="col-sm-2 col-form-label text-right" >Reserve price</label>
          <div class="col-sm-10">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" id="auctionReservePrice" name="auctionReservePrice" step="any">
            </div>
            <small id="reservePriceHelp" class="form-text text-muted">Optional. Auctions that end below this price will not go through. This value is not displayed in the auction listing.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">Start date</label>
          <div class="col-sm-10">
            <div class="input-group"> 
            <div class="input-group-prepend">
            <button type="button" id="startNow" class="" onclick="currDate()">Start Now</button>
              </div>
            <input type="datetime-local" class="form-control" id="auctionStartDate" name="auctionStartDate">
            </div>
            <small id="startDateHelp" class="form-text text-muted"><span class="text-danger" id="startDateDanger">* Required.</span> Day for the auction to end. Day must be in the future or now</small>
            
            
          </div>
          

        </div>
        <div class="form-group row">
          <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">End date</label>
          <div class="col-sm-10">
            <input type="datetime-local" class="form-control" id="auctionEndDate" name="auctionEndDate">
            <small id="endDateHelp" class="form-text text-muted"><span class="text-danger" id="endDateDanger">* Required.</span> Day for the auction to end. Date must be after start date</small>
          </div>
        </div>
        <button type="submit" class="btn btn-primary form-control">Create Auction</button>
      </form> -->
            </div>
        </div>
    </div>

</div>