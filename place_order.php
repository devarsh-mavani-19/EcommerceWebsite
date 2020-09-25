<?php
require('connection.php');
require("functions.php");
session_start();
if (isset($_GET['id']) && $_GET['id'] != '') {
    $id = $_GET['id'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <?php require('htmlcommon.php') ?>
</head>

<body>
    <?php require("nav.php") ?>
</body>

</html>