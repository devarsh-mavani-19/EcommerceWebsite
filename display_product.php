<?php
require('connection.php');
require('functions.php');
require('cart_functions.php');
session_start();
$user_msg = '';
$product_exist = true;
if (isset($_GET['id']) && ($_GET['id'] != '')) {
    $id = get_safe_value($con, $_GET['id']);
    $select_sql = "select * from ecommerce_product where id='$id'";
    $res = mysqli_query($con, $select_sql);
    if (mysqli_error($con)) {
        $user_msg = 'failed to load data ' . mysqli_error($con);
        $product_exist = false;
    } else {
        if (mysqli_num_rows($res) <= 0) {
            $user_msg = 'No Data Found';
            $product_exist = false;
        } else {
            if (isset($_POST['submit']) && isset($_POST['id']) && isset($_POST['quantity'])) {
                $cart_id = $_POST['id'];
                $cart_qty = $_POST['quantity'];
                if ($cart_id != '' && $cart_qty != '') {
                    update_product($cart_id, $cart_qty);
                    header('location:cart.php');
                    die();
                }
            }
            $data = mysqli_fetch_assoc($res);
        }
    }
} else {
    $product_exist = false;
    $user_msg = 'Error 404 product not found';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Product</title>
    <?php require('htmlcommon.php') ?>
</head>

<body>

    <?php require('nav.php') ?>

    <div class="container-fluid">

        <?php if ($product_exist) { ?>

            <div class='row'>

                <div class="col-4">

                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src=<?php echo "./media/products/" . $data['image'] ?> width="1024" height="500px" class="d-block w-100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-4'>
                    <h1>
                        <?php echo $data['product_name']; ?>
                    </h1>
                    <h6>
                        <?php echo "M.R.P Rs <strike>" . $data['product_mrp'] . "</strike>"; ?>
                    </h6>
                    <h6>
                        <?php echo "Deal of day : Rs " . $data['product_price']; ?>
                    </h6>

                    <p style="color: red;font-size: 13px">
                        <?php echo $data['short_description']; ?>
                    </p>
                    <p style="color: green;font-size: 16px">
                        <?php echo $data['description']; ?>
                    </p>
                </div>
                <div class="col-4">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Quantity</h5>
                            <form method='POST'>
                                <select class="custom-select" name='quantity'>
                                    <?php for ($i = 1; $i <= $data['qty']; $i++) { ?>
                                        <?php echo "<option value='$i'>" . $i . "</option>" ?>
                                    <?php } ?>
                                </select>
                                <input hidden type="text" name='id' value=<?php echo $id ?> />
                                <?php echo ($data['qty'] == 0 ? "" : "<a href='cart.php' class='btn btn-success'>Check out</a>") ?>
                                <input type="submit" name=' submit' <?php echo ($data['qty'] == 0 ? "disabled" : "") ?> value="Add to Cart" class="btn btn-primary" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <?php if (isset($user_msg) && $user_msg != '') {
            ?>
                <div class="alert alert-danger m-5" role="alert">
                    <?php echo $user_msg; ?>
                </div>
            <?php } ?>
        <?php } ?>

    </div>




</body>

</html>