<?php
require("connection.php");
require('functions.php');
require("cart_functions.php");
session_start();
$user_msg = '';
//valid user
if (isset($_SESSION['username']) && $_SESSION['username'] != '') {
}
$select_sql = "select * from ecommerce_category where status='1';";
$res = mysqli_query($con, $select_sql);
$res_copy = $res;
if (mysqli_error($con)) {
    $user_msg .= ' Failed to retrive Data.';
    $res = "";
}
$select_sql = "select id, image from ecommerce_product";
$products_carousel = mysqli_query($con, $select_sql);
if (mysqli_error($con)) {
    $user_msg .= ' Failed to retrive Data.';
    $products_carousel = "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        .mylink:hover {
            text-decoration: none;
            color: white;
        }

        .mylink {
            color: white;
        }
    </style>
    <?php require('htmlcommon.php'); ?>
</head>

<body>
    <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Ecommerce</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Categories
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        

                    </div>
                </li>

            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav> -->

    <?php require('nav.php') ?>
    <div class='container'>
        <?php if (isset($user_msg) && $user_msg != '') {
        ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $user_msg; ?>
            </div>
        <?php } ?>
        <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php if ($products_carousel != '') {
                    $first = true;
                    while ($row = mysqli_fetch_assoc($products_carousel)) {
                        $id = $row['id'];
                        $image = $row['image'];
                ?>
                        <div class="carousel-item <?php echo ($first ? "active" : ""); ?>">
                            <a href="display_product.php?id=<?php echo $id ?>">
                                <img width="500px" height="500px" src=<?php echo "./media/products/" . $image ?> class="d-block w-100">
                            </a>
                        </div>
                <?php $first = false;
                    }
                }  ?>
                <a class="carousel-control-prev" href="#carouselExampleSlidesOnly" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleSlidesOnly" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

            <div class="row">
                <?php while ($roww = mysqli_fetch_assoc($res_copy)) {
                    $nav_category_name = $roww['name'];
                    $nav_id = $roww['id'];
                    $nav_image = $roww['image'];
                    if ($nav_image != '' && isset($nav_image)) {
                    } else {
                        $nav_image = 'default_category.png';
                    }
                ?>
                    <div class="col">
                        <div class="card mt-5 text-white bg-dark" style="width: 18rem;">
                            <img src=<?php echo "./media/categories/" . $nav_image; ?> class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-text"><?php echo "<a class='mylink' href='products.php?id=" . $nav_id . "''>" . $nav_category_name . "</a>" ?></h5>
                            </div>
                        </div>
                    </div> <?php } ?> </div>
        </div>

    </div>
</body>

</html>