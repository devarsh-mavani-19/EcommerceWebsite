<?php
require('connection.php');
require('functions.php');
session_start();
if (isset($_GET['id']) && $_GET['id'] != '') {
    $order_id = $_GET['id'];
    $select_query = "select order_details.*, ecommerce_product.* from order_details, ecommerce_product where oid = '$order_id' and ecommerce_product.id = order_details.pid";
    $res = mysqli_query($con, $select_query);
    if (mysqli_error($con)) {
        die("Failed to fetch details" . mysqli_error($con));
    }
    $select_sql = "select orders.*, users.username username from orders, users where users.id = orders.uid and orders.id='$order_id';";
    $res2 = mysqli_query($con, $select_sql);
    if (mysqli_error($con)) {
        die("Error " . mysqli_errno($con));
    }
} else {
    echo "Order Details Not Found";
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <?php require("htmlcommon.php") ?>
</head>

<body>
    <?php require('nav.php'); ?>
    <div class="container">
        <table class='table'>
            <caption>Order</caption>
            <thead>
                <tr>
                    <th>
                        id
                    </th>
                    <th>
                        user id
                    </th>
                    <th>
                        username
                    </th>
                    <th>
                        total
                    </th>
                    <th>
                        Status
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($res2)) {
                    $id = $row['id'];
                    $uid = $row['uid'];
                    $username = $row['username'];
                    $total = $row['price'];
                ?>
                    <tr>
                        <td>
                            <?php echo $order_id; ?>
                        </td>
                        <td>
                            <?php echo $uid; ?>
                        </td>
                        <td>
                            <?php echo $username; ?>
                        </td>
                        <td>
                            <?php echo "Rs. " .  $total; ?>
                        </td>
                        <td>
                            <?php echo "shipped"; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <hr>
        <hr>
        <hr>

        <table class='table'>
            <caption>
                Order Details
            </caption>
            <thead>
                <tr>
                    <th>
                        id
                    </th>
                    <th>
                        product name
                    </th>
                    <th>
                        price
                    </th>
                    <th>
                        quantity
                    </th>
                    <th>
                        Total
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($res)) {
                    $pid = $row['id'];
                    $name = $row['product_name'];
                    $price = $row['price'];
                    $quantity = $row['qty'];

                ?>
                    <tr>
                        <td>
                            <?php echo $pid; ?>
                        </td>
                        <td>
                            <?php echo $name ?>
                        </td>
                        <td>
                            <?php echo $price ?>
                        </td>
                        <td>
                            <?php echo $quantity ?>
                        </td>
                        <td>
                            <?php echo $price * $quantity; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>