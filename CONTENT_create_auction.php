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
      <form method="post" action="create_auction_result.php" id="createAuctionForm" enctype="multipart/form-data">
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
            <button type="button" id="startNow" class="btn btn-secondary" onclick="currDate()">Start Now</button>
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
      </form>
      <script>
        const form = document.getElementById("createAuctionForm");
        const titleInput = document.getElementById("auctionTitle");
        const titleHelp = document.getElementById("titleDanger");
        const detailsInput = document.getElementById("auctionDetails");
        const detailsHelp = document.getElementById("detailsDanger");
        const categoryInput = document.getElementById("auctionCategory");
        const categoryHelp = document.getElementById("categoryDanger");
        const conditionInput = document.getElementById("auctionCondition");
        const conditionHelp = document.getElementById("conditionDanger");
        const imageInput = document.getElementById("auctionImage");  
        const removeButton = document.getElementById("removeImage");
        const imageHelp = document.getElementById("imageHelp")
        const startPriceInput = document.getElementById("auctionStartPrice");
        const startPriceHelp = document.getElementById("startPriceDanger");
        const reservePriceInput = document.getElementById("auctionReservePrice");
        const startDateInput = document.getElementById("auctionStartDate")
        const startDateHelp = document.getElementById("startDateDanger");
        const endDateInput = document.getElementById("auctionEndDate");
        const endDateHelp = document.getElementById("endDateDanger");
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
          const valid = validateForm();
          console.log(valid);
          if (!valid) {
            event.preventDefault();
          }
        })
        titleInput.addEventListener("input", function () {
          validateTitle();
        })
        detailsInput.addEventListener("input", function () {
          validateDetails();
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
        reservePriceInput.addEventListener("input", function () {
          validateReservePrice();
        })
        startDateInput.addEventListener("input", function() {
          validateStartDate()
          validateEndDate()
        })
        endDateInput.addEventListener("input", function () {
          validateEndDate();
        })
        
        function validateForm() {
          let isValid = true;
          if (!validateTitle()) {
            isValid = false;
          } 
          if (!validateCategory()) {
            isValid = false
          }  if (!validateCondition()) {
            isValid = false
          }  if (!validateImage()){
            isValid = false;
          } if (!validateStartPrice()) {
            isValid = false
          } if (!validateReservePrice()){
            isValid = false
          } if (!validateStartDate()) {
            isValid = false
          }  if (!validateEndDate()) {
            isValid = false
          } 
          return isValid;
        }
        function validateTitle() {
          const titleValue = titleInput.value.trim()
          if (titleValue === ""){
            titleHelp.classList.remove("d-none")
            return false
          } else if (titleValue.length > 500){
            titleHelp.classList.remove("d-none")
            return false
          }
          else {
            titleHelp.classList.add("d-none")
            return true
          }
        }
        function validateDetails() {
          const detailsValue = detailsInput.value.trim()
          if (detailsValue === ""){
            detailsHelp.classList.remove("d-none")
            return false
          } else if (detailsValue.length > 1000){
            detailsHelp.classList.remove("d-none")
            return false
          }
          else {
            detailsHelp.classList.add("d-none")
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
                imageInput.value = null;
                imageHelp.innerHTML = "File is too large. Maximum size is 5 MB.";
                return false;
          }
          imageHelp.innerHTML = "";
          return true

        }

        function validateStartPrice() {

          if (isNaN(startPriceInput.value) || parseFloat(startPriceInput.value) < 0 || startPriceInput.value === "") {
            startPriceHelp.classList.remove("d-none")
            return false
          } else {
            startPriceHelp.classList.add("d-none")
            return true
          }
        }

        function validateReservePrice() {
          if (parseFloat(reservePriceInput.value) < 0 || isNaN(reservePriceInput.value)) {
            return false
          } else {
            return true
          }
        }

        function validateStartDate() {
          const selectedDate = new Date(startDateInput.value)
          const currDate = new Date()
          
          if (selectedDate < currDate || startDateInput.value === "") {
            startDateHelp.classList.remove("d-none")
            return false
          } else {
            startDateHelp.classList.add("d-none")
            return true
          }
        }
        function currDate() {
          var currDate = new Date();
          currDate.setMinutes(currDate.getMinutes()+1);
          startDateInput.value =currDate.toISOString().slice(0,16);
          startDateHelp.classList.add("d-none");
          validateEndDate()
        }

        function validateEndDate() {
          const selectedDate = new Date(endDateInput.value)
          const startDate = new Date(startDateInput.value)
          const currDate = new Date()
          if (selectedDate <= currDate || selectedDate <= startDate || endDateInput.value === "") {
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


