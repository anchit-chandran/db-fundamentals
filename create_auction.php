<?php include_once("header.php")?>

<?php
/* (Uncomment this block to redirect people without selling privileges away from this page)
  // If user is not logged in or not a seller, they should not be able to
  // use this page.
  if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] != 'seller') {
    header('Location: browse.php');
  }
*/
include_once 'database.php';
$query = 'SELECT categoryId, categoryName FROM Category';
$result = runQuery($query);
$categories = array();
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
  }

} else {
  echo "Error";
}
$query = 'SELECT categoryId, subCategoryName, subCategoryId FROM SubCategory';
$result = runQuery($query);
$subcategories = array();
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $subcategories[] = $row;
  }

} else {
  echo "Error";
}

?>

<div class="container">

<!-- Create auction form -->
<div style="max-width: 800px; margin: 10px auto">
  <h2 class="my-3">Create new auction</h2>
  <div class="card">
    <div class="card-body">
      <!-- Note: This form does not do any dynamic / client-side / 
      JavaScript-based validation of data. It only performs checking after 
      the form has been submitted, and only allows users to try once. You 
      can make this fancier using JavaScript to alert users of invalid data
      before they try to send it, but that kind of functionality should be
      extremely low-priority / only done after all database functions are
      complete. -->
      <form method="post" action="create_auction_result.php" id="createAuctionForm">
        <div class="form-group row">
          <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Title of auction</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="auctionTitle" placeholder="e.g. Black mountain bike">
            <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> A short description of the item you're selling, which will display in listings.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionDetails" class="col-sm-2 col-form-label text-right">Details</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="auctionDetails" rows="4"></textarea>
            <small id="detailsHelp" class="form-text text-muted">Full details of the listing to help bidders decide if it's what they're looking for.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionCategory" class="col-sm-2 col-form-label text-right">Category</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionCategory">
            <option value="">Choose...</option>
            <?php
            foreach ($categories as $category) {
                echo "<option value=\"{$category['categoryId']}\">{$category['categoryName']}</option>";
            }
            ?>
            </select>
            <small id="categoryHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a category for this item.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionSubCategory" class="col-sm-2 col-form-label text-right">Subcategory</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionSubCategory">
              
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

            <small id="subcategoryHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a subcategory for this item.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionCondition" class="col-sm-2 col-form-label text-right">Item Condition</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionCondition">
              <option value="">Choose...</option>
              <option value="brandNew">Brand New</option>
              <option value="slightlyUsed">Slightly Used</option>
              <option value="used">Used</option>
            </select>
            <small id="conditionHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a condition for this item.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionImage" class="col-sm-2 col-form-label text-right">Item Image</label>
          <div class="col-sm-10">
          <input type="file" id="auctionImage" name="auctionImage" accept=".jpg, .jpeg, .png," required>
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
              <input type="number" class="form-control" id="auctionStartPrice">
            </div>
            <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Initial bid amount. Amount must be a valid number and greater than or equal to 0</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionReservePrice" class="col-sm-2 col-form-label text-right">Reserve price</label>
          <div class="col-sm-10">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" id="auctionReservePrice">
            </div>
            <small id="reservePriceHelp" class="form-text text-muted">Optional. Auctions that end below this price will not go through. This value is not displayed in the auction listing.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">End date</label>
          <div class="col-sm-10">
            <input type="datetime-local" class="form-control" id="auctionEndDate">
            <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Day for the auction to end. Day must be in the future.</small>
          </div>
        </div>
        <button type="submit" class="btn btn-primary form-control">Create Auction</button>
      </form>
      <script>
        const form = document.getElementById("createAuctionForm");
        const titleInput = document.getElementById("auctionTitle");
        const titleHelp = document.getElementById("titleHelp");
        const categoryInput = document.getElementById("auctionCategory");
        const categoryHelp = document.getElementById("categoryHelp");
        const conditionInput = document.getElementById("auctionCondition");
        const conditionHelp = document.getElementById("conditionHelp");
        const imageInput = document.getElementById("auctionImage");  
        const removeButton = document.getElementById("removeImage");
        const imageHelp = document.getElementById("imageHelp")
        const startPriceInput = document.getElementById("auctionStartPrice");
        const startPriceHelp = document.getElementById("startBidHelp");
        const endDateInput = document.getElementById("auctionEndDate");
        const endDateHelp = document.getElementById("endDateHelp");
        function clearImage() {
          
          removeButton.classList.add("d-none");
          imageInput.value = "";
          imageHelp.innerHTML = "Upload image for this listing (.jpg, .jpeg, .png) max: 5 MB";

        }
        imageInput.addEventListener("change", function () {
          validateImage();
          if (this.value!="") {
            removeButton.classList.remove("d-none")
          } else {
            
            removeButton.classList.add("d-none")
          } 

        })
        form.addEventListener("submit", function (event) {
          if (!validateForm()) {
            event.preventDefault();
          }
        })
        titleInput.addEventListener("input", function () {
          validateTitle();
        })
        categoryInput.addEventListener("change", function () {
          
          validateCategory();
        })
        conditionInput.addEventListener("change", function () {
          validateCondition();
        })
        startPriceInput.addEventListener("input", function () {
          validateStartPrice();
        })
        endDateInput.addEventListener("input", function () {
          validateEndDate();
        })
        function validateForm() {
          let isValid = true;
          if (!validateTitle()) {
            isValid = false;
          } if (!validateCategory()) {
            isValid = false
          }  if (!validateCondition()) {
            isValid = false
          }  if (!validateStartPrice()) {
            isValid = false
          }  if (!validateEndDate()) {
            isValid = false
          } 
        }
        function validateTitle() {
          const titleValue = titleInput.value.trim()
          if (titleValue === ""){
            titleHelp.classList.remove("d-none")
            return false
          } else {
            titleHelp.classList.add("d-none")
            return true
          }
        }
        function validateCategory() {
          if (categoryInput.value === "") {
            categoryHelp.classList.remove("d-none")
            return false
          } else {
            categoryHelp.classList.add("d-none")
            return true
          }
        }
        function validateCondition() {
          if (conditionInput.value === "") {
            conditionHelp.classList.remove("d-none")
            return false
          } else {
            conditionHelp.classList.add("d-none")
            return true
          }
        }
        function validateImage() {
          const maxSize = 5242880;
          if (imageInput.files.length === 0) {
            imageHelp.innerHTML = "Upload image for this listing (.jpg, .jpeg, .png) max: 5 MB"
            return true
          }
          const image = imageInput.files[0]
          const imageSize = image.size;
          if (imageSize > maxSize) {
                imageInput.value = "";
                imageHelp.innerHTML = "File is too large. Maximum size is 5 MB.";
                return false;
          }
          imageHelp.innerHTML = "";
          return true

        }

        function validateStartPrice() {

          if (isNaN(startPriceInput.value) || parseFloat(startPriceInput.value) < 0 || startPriceInput.value === "") {
            // startPriceHelp.textContent = "Starting Price must be a valid number and greater than or equal to 0.";
            startPriceHelp.classList.remove("d-none")
            return false
          } else {
            startPriceHelp.classList.add("d-none")
            return true
          }
        }
        function validateEndDate() {
          const selectedDate = new Date(endDateInput.value)
          const currDate = new Date()
          if (selectedDate <= currDate) {
            endDateHelp.classList.remove("d-none")
            return false
          } else {
            endDateHelp.classList.add("d-none")
            return true
          }
        }

      </script>
    </div>
  </div>
</div>

</div>


<?php include_once("footer.php")?>