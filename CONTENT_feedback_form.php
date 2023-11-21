<?php
$product_id = $_GET['product_id'];
$product_name = runQuery("SELECT name FROM Product WHERE productId = {$product_id}")->fetch_assoc()['name'];
?>

<div class="container">
  <div style="max-width: 800px; margin: 10px auto">
    <h2 class="my-3">Create Feedback</h2>
    <p>You were the highest bidder for this product: 
      <span class="fw-bold">
      <?php echo $product_name; ?>
      </span>
    </p>
    
    <div class="card">
      <div class="card-body">
        <form method="post" action="create_feedback.php" id="createFeedbackForm" enctype="multipart/form-data">

        <div>
        <label for="rating"" class="col-sm col-form-label text-right">Rating (required)</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="rating" id="inlineRadio1" value="1" required>
          <label class="form-check-label" for="inlineRadio1">1</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="rating" id="inlineRadio2" value="2">
          <label class="form-check-label" for="inlineRadio2">2</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="rating" id="inlineRadio3" value="3">
          <label class="form-check-label" for="inlineRadio3">3</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="rating" id="inlineRadio4" value="4">
          <label class="form-check-label" for="inlineRadio4">4</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="rating" id="inlineRadio5" value="5">
          <label class="form-check-label" for="inlineRadio5">5</label>
        </div>
    
          <div><label for="comment"" class=" col-sm col-form-label text-right">Message (max 2000 characters)</label></div>
          <textarea name="comment" class="form-control mb-2" rows="6" maxlength="2000"></textarea>
          <input name="user_id" type="hidden" value=<?php echo $_SESSION['userId']; ?>>
          <input name="product_id" type="hidden" value=<?php echo $product_id; ?>>
          <button type="submit" class="btn btn-primary form-control">Send/Update Feedback</button>
        </form>
      </div>
    </div>
  </div>
</div>