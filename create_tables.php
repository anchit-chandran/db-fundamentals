<?php
date_default_timezone_set('Europe/London');
// order matters: create tables with no FK first (topological sort)

include 'tables/create_category_seed.php';
include 'tables/create_subCategory_seed.php';
include 'tables/create_user_seed.php';

include 'tables/create_payment_seed.php';
include 'tables/create_address_seed.php';

include 'tables/create_product_seed.php';

include 'tables/create_watchItem_seed.php';

include 'tables/create_feedback_seed.php';
include 'tables/create_bid_seed.php';
include 'tables/create_orders_seed.php';

?>