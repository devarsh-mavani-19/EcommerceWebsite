<?php
function remove_product($pid)
{
    if (isset($_SESSION['cart'][$pid])) {
        unset($_SESSION['cart'][$pid]);
    }
}

function update_product($pid, $quantity)
{
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['qty'] = $_SESSION['cart'][$pid]['qty'] + $quantity;
    } else {
        $_SESSION['cart'][$pid]['qty'] = $quantity;
    }
}

function replace_qty($pid, $quantity)
{
    if (isset($_SESSION['cart'][$pid])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$pid]);
        } else
            $_SESSION['cart'][$pid]['qty'] = $quantity;
    }
}

function get_total_unique_products()
{
    if (isset($_SESSION['cart'])) {
        return count($_SESSION['cart']);
    } else {
        return 0;
    }
}

function empty_cart()
{
    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
}
