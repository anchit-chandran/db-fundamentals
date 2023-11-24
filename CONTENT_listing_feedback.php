<?php 
    $rating = $feedback['rating']
?>

<div>
    <h3>Product Feedback</h3>
    <h4 class="fw-lighter">
        Product rating: 
        <?php 
            include("CONTENT_rating.php");
        ?>
        <?php echo "( {$rating} / 5 )"; ?>
    </h4>

    <?php 
        if ($feedback['comment'] != null) {
            echo "<p> Comment: ";
            echo $feedback['comment'];
            echo "</p>";
        }
    ?>
</div>