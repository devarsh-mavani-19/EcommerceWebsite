<?php
require('connection.php');
require('functions.php');
require('cart_functions.php');
session_start();
$user_msg = '';
$error_exist = false;
if (isset($_GET['id']) && $_GET['id'] != '') {
    //show product of current category
    $category_id = get_safe_value($con, $_GET['id']);
    $select_sql = "select * from ecommerce_product where category_id='$category_id'";
    $select2 = "select name from ecommerce_category where id='$category_id'";
    $res2 = mysqli_query($con, $select2);
    if (mysqli_error($con)) {
        $user_msg = " error fetching products.";
        $error_exist = true;
    } else {
        $c_name = mysqli_fetch_assoc($res2)['name'];
    }
    if (isset($c_name) && $c_name != '') {
        $res = mysqli_query($con, $select_sql);
        if (mysqli_error($con)) {
            $user_msg = " error fetching products.";
            $error_exist = true;
        }
    } else {
        $res = showAllProducts($user_msg, $error_exist);
    }
} else {
    //show all products in database
    $res = showAllProducts($user_msg, $error_exist);
}
function showAllProducts(&$user_msg, &$error_exist)
{
    global $con;
    $select_sql = "select * from ecommerce_product";
    $res = mysqli_query($con, $select_sql);
    if (mysqli_error($con)) {
        $error_exist = true;
        $user_msg = " error fetching products.";
    } else {
        return $res;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <?php require('htmlcommon.php') ?>

</head>

<body>
    <?php require('nav.php'); ?>
    <div class="container">
        <div class="jumbotron">
            <h1 class='text-center'>
                <?php echo ucfirst((isset($c_name) ? $c_name : "Showing all products")); ?>
            </h1>
        </div>
    </div>
    <div class="container">
        <div class='row'>


            <?php
            if (!$error_exist) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $product_id = $row['id'];
                    $product_name = $row['product_name'];
                    $product_mrp = $row['product_mrp'];
                    $product_price = $row['product_price'];
                    $qty = $row['qty'];
                    $image = $row['image'];
            ?>
                    <div class="col-4">
                        <div class="card" style="width: 18rem;">
                            <img src=<?php echo "./media/products/" . $image ?> alt="product image" class="card-img-top" />
                            <div class="card-body">
                                <h5 class="card-title"> <?php echo "<a href='display_product.php?id=" . $product_id . "'>" . $product_name . "</a>"; ?></h5>
                                <p class="card-text"><?php echo "<strike>MRP $" . $product_mrp . "</strike>"; ?>
                                    <?php echo "$" . $product_price; ?></p>
                                <p class="card-text"><?php echo "quantity : " . $qty; ?></p>
                            </div>
                        </div>
                    </div>
            <?php }
            } ?>

        </div>
        <?php if (isset($user_msg) && $user_msg != '') {
        ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $user_msg; ?>
            </div>
        <?php } ?>
    </div>

</body>

</html>