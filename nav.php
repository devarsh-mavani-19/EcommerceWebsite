<?php
$nav_sql = "select * from ecommerce_category where status='1';";
$nav_res = mysqli_query($con, $nav_sql);
if (mysqli_error($con)) {
    echo "Failed to retrive Data";
    $nav_res = "";
}

?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href=""><?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : "<a href='login.php?ref=" . basename($_SERVER['PHP_SELF']) . "&id=" . (isset($_GET['id']) ? $_GET['id'] : "") . "'>Login</a>")  ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Categories
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php while ($roww = mysqli_fetch_assoc($nav_res)) {
                        $nav_category_name = $roww['name'];
                        $nav_id = $roww['id'];
                    ?>

                        <?php echo "<a class='dropdown-item' href='products.php?id=" . $nav_id . "''>" . $nav_category_name . "</a>" ?>

                    <?php } ?>

                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="cart.php">Cart (<?php echo get_total_unique_products() . ' items'; ?>)</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>