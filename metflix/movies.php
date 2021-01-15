<?php
    require_once("includes/header.php");

    $preview = new PreviewProvider($con, $userLoggedIn);
    echo $preview->createMoviePreviewVideo();

    $containers = new CategoryContainers($con, $userLoggedIn);
    echo $containers->showMovieCategories();

?>