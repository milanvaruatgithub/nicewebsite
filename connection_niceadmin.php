<?php

$kg_niceadmin_con = mysqli_connect("localhost", "root", "", "niceadmin");

if (!$kg_niceadmin_con) {
    die("Connection failed: " . mysqli_connect_error());
}
