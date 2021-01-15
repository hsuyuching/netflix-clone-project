<?php
    require_once("includes/config.php");
    require_once("includes/classes/PreviewProvider.php");
    require_once("includes/classes/CategoryContainers.php");
    require_once("includes/classes/Entity.php");
    require_once("includes/classes/EntityProvider.php");
    require_once("includes/classes/ErrorMessage.php");
    require_once("includes/classes/SeasonProvider.php");
    require_once("includes/classes/Season.php");
    require_once("includes/classes/Video.php");
    require_once("includes/classes/VideoProvider.php");
    require_once("includes/classes/User.php");

    if (!$_SESSION["userLoggedIn"]) {
        header("Location: register.php");
    }
    $userLoggedIn = $_SESSION["userLoggedIn"];

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to Metflix</title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css"/>

        <!-- to enable mute and unmute function for button -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="assets/js/script.js"></script>
        <script src="https://kit.fontawesome.com/9ad3b8c2b2.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class='wrapper'>
<?php 
    if(!isset($hideNav)){
        include_once("navBar.php");
    }
?>