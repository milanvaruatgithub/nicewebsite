<?php

$kg_con = mysqli_connect("localhost", "root", "", "NiceWebsite");

if (!$kg_con) {
    die("Connection failed: " . mysqli_connect_error());
}
