<?php
require('connection.php');
require('functions.php');
session_start();
if (isset($_SESSION['username']) && $_SESSION['username'] != '') {
} else {
    echo "Please login  <a href='login.php'>Here</a>";
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>

<body>

</body>

</html>