<?php 
require("connection.php");
require('functions.php');
session_start();
if (isset($_SESSION['username']) && $_SESSION['username'] != '') {
    //valid user
    echo $_SESSION['username'] . "</br>";
    $select_sql = "select * from ecommerce_category where status='1';";
    $res = mysqli_query($con, $select_sql);
    if (mysqli_error($con)) {
        echo "Failed to retrive Data";
        $res = "";
    }
} else {
    header('location:login.php');
}
