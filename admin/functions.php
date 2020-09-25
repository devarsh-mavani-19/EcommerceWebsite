<?php
function prx($arr)
{
    echo "<pre>";
    print_r($arr);
    die();
}

function pr($arr)
{
    echo "<pre>";
    print_r($arr);
}

function get_safe_value($con, $str)
{
    if ($str != '') {
        return mysqli_real_escape_string($con, $str);
    }
}
