<?php
require('connection.php');
require('functions.php');
session_start();
if (!isset($_SESSION['ADMIN_LOGIN'])) {
    header('location:login.php');
} else {

    if (isset($_GET['type'])) {
        $type = get_safe_value($con, $_GET['type']);

        if ($type == "status") {
            $id = $_GET['id'];
            if ($_GET['operation'] == "1") {
                $operation = "1";
            } else {
                $operation = "0";
            }
            $update_status = "update ecommerce_product set status = '$operation' where id='$id'";
            mysqli_query($con, $update_status);
        }

        if ($type == "delete") {
            $id = get_safe_value($con, $_GET['id']);
            $delete_sql = "delete from ecommerce_product where id='$id'";
            mysqli_query($con, $delete_sql);
        }
    }

    $sql = "select ecommerce_product.*, ecommerce_category.name category_name from ecommerce_product, ecommerce_category where ecommerce_category.id = ecommerce_product.category_id order by product_name;";
    $res = mysqli_query($con, $sql);

    if (mysqli_error($con)) {
        echo "<script>alert('Failed to retrive records');</script>";
        $res = "";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>products</title>
    <?php require('htmlcommon.php') ?>
</head>

<body>
    <?php require("nav.php") ?>
    <div class="jumbotron">
        <a href="addcategory.php" class='btn btn-primary'>Add New Category</a>
        <a href="addproduct.php" class='btn btn-primary'>Add New Product</a>
        <b class='ml-5' style="font-size: 40px;">Products</b>
    </div>
    <div class="container">
        <table class="table">
            <thead>

                <tr>
                    <th>
                        Id
                    </th>
                    <th>
                        Category
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Image
                    </th>
                    <th>
                        MRP
                    </th>
                    <th>
                        Price
                    </th>
                    <th>
                        Qty
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Action
                    </th>
                    <th>
                        Action2
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($res) && $res != "") {
                    while ($row = mysqli_fetch_assoc($res)) { ?>
                        <tr>
                            <td>
                                <?php echo $row['id']; ?>
                            </td>
                            <td>
                                <?php echo $row['category_name']; ?>
                            </td>
                            <td>
                                <?php echo $row['product_name']; ?>
                            </td>
                            <td>

                                <img width="20px" height="20px" src='../media/products/<?php echo $row['image']; ?>' />
                            </td>
                            <td>
                                <?php echo $row['product_mrp']; ?>
                            </td>
                            <td>
                                <?php echo $row['product_price']; ?>
                            </td>
                            <td>
                                <?php echo $row['qty']; ?>
                            </td>
                            <td>
                                <?php echo "<a href='?type=status&operation=" . ($row['status'] == "0" ? "1" : "0") . "&id=" . $row['id'] . "'>" . ($row['status'] == "1" ? "Active" : "Deactive") . "</a>" ?>
                            </td>
                            <td>
                                <?php echo "<a href='?type=delete&id=" . $row['id'] . "'>" . "delete" . "</a>" ?>
                            </td>
                            <td>
                                <?php echo "<a href='addproduct.php?type=update&id=" . $row['id'] . "'>" . "update" . "</a>" ?>
                            </td>
                        </tr>
                <?php }
                }
                ?>
            </tbody>
        </table>
    </div>


</body>

</html>