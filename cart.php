<?php
require('connection.php');
require("functions.php");
require('cart_functions.php');
session_start();
$user_msg = '';
$cart_empty = false;
$error_exist = false;
if (isset($_POST['submit_delete'])) {
    $pid = $_POST['pid'];
    remove_product($pid);
} else if (isset($_POST['submit_update'])) {
    $pid = $_POST['pid'];
    $new_qty = $_POST['new_qty'];
    $select_sql = "select qty from ecommerce_product where id = '$pid'";
    $r = mysqli_query($con, $select_sql);
    $ro = mysqli_fetch_assoc($r);

    if ($new_qty > $ro['qty']) {
        $user_msg = 'Failed to increase quantity. Not Enough Stock With Merchant';
    } else {
        replace_qty($pid, $new_qty);
    }
} else if (isset($_POST['submit_buy'])) {
    if (isset($_SESSION['username']) && $_SESSION['username'] != "") {

        $username = $_SESSION['username'];
        $uid_select = "select id from users where username = '$username'";
        $uid_res = mysqli_query($con, $uid_select);
        $failed_array = array();

        if (mysqli_error($con)) {
            $user_msg = "Failed to place order " . mysqli_error($con);
        } else {
            $uid = mysqli_fetch_assoc($uid_res)['id'];
            if (!isset($uid) || $uid == '') {
                $user_msg = "User Not logged in.";
            } else {
                if (!isset($_SESSION['cart'])) {
                    $cart_empty = true;
                } else {
                    $final_products = $_SESSION['cart'];

                    $cart_price = 0;
                    foreach ($final_products as $key => $value) {
                        $temp_sql = "select product_price from ecommerce_product where id='$key'";
                        $temp_res = mysqli_query($con, $temp_sql);
                        $temp_row = mysqli_fetch_assoc($temp_res);
                        $cart_price = $cart_price + ($value['qty'] * $temp_row['product_price']);
                    }
                    $insert_sql = "insert into orders(uid, price) values('$uid', '$cart_price')";
                    mysqli_query($con, $insert_sql);
                    $oid = mysqli_insert_id($con);
                    $final_products = $_SESSION['cart'];
                    foreach ($final_products as $key => $value) {
                        $temp_sql = "select * from ecommerce_product where id='$key'";
                        $temp_res = mysqli_query($con, $temp_sql);
                        $temp_row = mysqli_fetch_assoc($temp_res);
                        $temp_ori_qty = $temp_row['qty'];
                        $temp_pid = $temp_row['id'];
                        $temp_price = $temp_row['product_price'];
                        $new_qty = $value['qty'];


                        if (mysqli_error($con) || $new_qty > $temp_ori_qty) {
                            array_push($failed_array, mysqli_insert_id($con));
                            // die('error : ' . mysqli_error($con));
                            break;
                        } else {
                            $insert_sql = "insert into order_details(oid, pid, price, quantity) values('$oid', '$temp_pid', '$temp_price', '$new_qty');";
                            $update_sql = "update ecommerce_product set qty = qty - '$new_qty' where id = '$temp_pid'";
                            mysqli_query($con, $insert_sql);
                            mysqli_query($con, $update_sql);
                        }
                        // $cart_price = $cart_price + ($value['qty'] * $)
                        if (count($failed_array) > 0) {
                            $user_msg = "Failed to place Order";
                            for ($i = 0; $i < count($failed_array); $i++) {
                                $delete_sql = "delete from order_details where id = '" . $failed_array[$i] . "'";
                                mysqli_query($con, $delete_sql);
                            }
                            $delete_sql = "delete from orders where id = '$oid'";
                            mysqli_query($con, $delete_sql);
                        } else {
                            $user_msg_success = 'order placed Successfully';
                            empty_cart();
                            //remove items from database 
                        }
                    }
                }
            }
        }
    } else {
        $user_msg = "User not logged in <a href='login.php'>Click here to login</a>";
    }
}
if (isset($_SESSION['cart'])) {
    $cart_products = $_SESSION['cart'];
    if (count($cart_products) <= 0) {
        $cart_empty = true;
    }
} else {
    $cart_empty = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <?php require("htmlcommon.php") ?>
</head>

<body>
    <?php require("nav.php") ?>
    </br>


    <div class="container text-center">
        <?php if (!$cart_empty) { ?>

            <table class="table">
                <thead>
                    <tr>
                        <th>
                            Id
                        </th>

                        <th>
                            name
                        </th>
                        <th>
                            Image
                        </th>
                        <th>
                            price
                        </th>
                        <th>
                            Quantity
                        </th>
                        <th>
                            Update
                        </th>
                        <th>
                            Total
                        </th>
                        <th>
                            Delete
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($cart_products as $key => $value) {
                        $select_sql = "select * from ecommerce_product where id='$key'";
                        $res = mysqli_query($con, $select_sql);
                        if (!mysqli_error($con)) {
                            if (mysqli_num_rows($res) == 1) {
                                $row = mysqli_fetch_assoc($res);
                                $product_name = $row['product_name'];
                                $image_url = $row['image'];
                                $price = $row['product_price'];
                            }
                        }

                    ?>
                        <tr>
                            <td>
                                <?php echo $key; ?>
                            </td>

                            <td>
                                <?php echo $product_name ?>
                            </td>
                            <td>
                                <img src=<?php echo "./media/products/" . $image_url ?> width="50px" height="50px" alt="image" />

                            </td>
                            <td>
                                <?php echo $price; ?>
                            </td>
                            <td>
                                <?php echo $value['qty']; ?>
                            </td>
                            <td>
                                <form method="post">
                                    <div class="form-group">
                                        <input type="text" name="pid" hidden value=<?php echo $key ?>>
                                        <input type="number" min=0 class='form-control' name='new_qty' value=<?php echo $value['qty']; ?>>
                                        <input type="submit" class="btn btn-primary" name='submit_update' value='update' />
                                    </div>
                                </form>
                            </td>
                            <td>
                                <?php echo $price * $value['qty']; ?>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="text" name="pid" hidden value=<?php echo $key ?>>
                                    <input type="submit" class='btn btn-danger' name='submit_delete' value='delete' />
                                </form>
                            </td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
            <form method='POST'>
                <input type="submit" name="submit_buy" value='submit' class="btn btn-primary" />
            </form>
        <?php } else { ?>
            <img src='./media/assets/cart_empty.png' class='img-fluid' />
        <?php } ?>
        <?php if (isset($user_msg) && $user_msg != '') {
        ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $user_msg; ?>
            </div>
        <?php } ?>
        <?php if (isset($user_msg_success) && $user_msg_success != '') {
        ?>
            <div class="alert alert-success">
                <?php echo $user_msg_success; ?>
            </div>
        <?php } ?>
    </div>

</body>

</html>