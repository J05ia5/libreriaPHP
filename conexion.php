<?php
if (!isset($con)) {
    $con = new mysqli("localhost", "root", "", "bd_biblioteca");
    if ($con->connect_error) {
        die("error al conectarse: " . $con->connect_error);
    }
}
