<?php
    // where php go lives: http://localhost/phpmyadmin/
    ob_start(); // turn on output buffer
    session_start();
    
    date_default_timezone_set("America/Denver");
    try {
        // set the default username and password
        $con = new PDO("mysql:dbname=metflix;host=localhost", "root", "");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    catch (PDOExpection $e) {
        exit("Connection failed:" . $e->getMessage());
    }
?>