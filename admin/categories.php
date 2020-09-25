<?php
require('connection.php');
require('functions.php');
session_start();
if (isset($_SESSION['ADMIN_LOGIN'])) {

    if (isset($_GET['type'])) {
        $type = get_safe_value($con, $_GET['type']);

        if ($type == "status") {
            $id = $_GET['id'];
            if ($_GET['operation'] == "1") {
                $operation = "1";
            } else {
                $operation = "0";
            }
            $update_status = "update ecommerce_category set status = '$operation' where id='$id'";
            mysqli_query($con, $update_status);
        }

        if ($type == "delete") {
            $id = get_safe_value($con, $_GET['id']);
            $delete_sql = "delete from ecommerce_category where id='$id'";
            mysqli_query($con, $delete_sql);
        }
    }


    $sql = "select * from ecommerce_category order by name desc";
    $res = mysqli_query($con, $sql);
} else {
    header("location:login.php");
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
    <?php require('nav.php') ?>
    <div class="jumbotron">
        <a href="addcategory.php" class='btn btn-primary'>Add New Category</a>
        <a href="addproduct.php" class='btn btn-primary'>Add New Product</a>
        <b class='ml-5' style="font-size: 40px;">Category</b>
    </div>
    <div class="container">

        <table class="table">
            <thead>
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Action1
                    </th>
                    <th>
                        Action2
                    </th>
                </tr>
            </thead>
            <tbody>



                <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                ?>
                    <tr>
                        <td>
                            <?php echo $i; ?>
                        </td>
                        <td>
                            <?php echo $row['name']; ?>
                        </td>
                        <td>
                            <?php echo "<a href='?type=status&operation=" . ($row['status'] == "0" ? "1" : "0") . "&id=" . $row['id'] . "'>" . ($row['status'] == "1" ? "Active" : "Deactive") . "</a>" ?>
                        </td>
                        <td>
                            <?php echo "<a href='?type=delete&id=" . $row['id'] . "'>" . "delete" . "</a>" ?>
                        </td>
                        <td>
                            <?php echo "<a href='addcategory.php?type=update&id=" . $row['id'] . "'>" . "update" . "</a>" ?>
                        </td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>

        </table>
    </div>

</body>

</html>