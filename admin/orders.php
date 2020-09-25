<?php
require('connection.php');
require('functions.php');
session_start();
if (isset($_SESSION['ADMIN_LOGIN']) && $_SESSION['ADMIN_LOGIN'] != '') {
    if (isset($_GET['filter'])) {
        $filter_type = $_GET['filter'];
        switch ($filter_type) {
            case "shipped":
                $select_sql = "select orders.*, users.username from orders, users where users.id = orders.uid;";
                break;
            default:
                $select_sql = "select orders.id, users.username from orders,users where orders.uid = users.id;";
                break;
        }
    } else {
        $select_sql = "select orders.*, users.username username from orders, users where users.id = orders.uid;";
        // $select_sql = "select * from orders";
    }
    $res = mysqli_query($con, $select_sql);
    if (mysqli_error($con)) {
        die('error : ' . mysqli_error($con));
    }
} else {
    header('location:login.php');
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <?php require('htmlcommon.php') ?>
</head>

<body>
    <?php require('nav.php') ?>
    <div class="container">
        <table class='table'>
            <thead>
                <tr>
                    <td>
                        id
                    </td>
                    <td>
                        user id
                    </td>
                    <td>
                        username
                    </td>
                    <td>
                        total
                    </td>
                    <td>
                        Status
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id'];
                    $uid = $row['uid'];
                    $username = $row['username'];
                    $total = $row['price'];
                ?>
                    <tr>
                        <td>
                            <?php echo "<a href='order_details.php?id=" . ($id) . "'/>" . $id . "</a>"; ?>
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
    </div>
</body>

</html>