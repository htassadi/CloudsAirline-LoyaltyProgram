<?php

// Start session
session_start();

include("config/db_connect.php");
include("config/user_connect.php");

?>

<!DOCTYPE html>
<html lang="en">

    <!-- HEADER TAG IN ALL PAGES (starting body included), CSS Included-->
    <?php include('templates/header.php'); ?>

    <div class="jumbotron bg-secondary text-white">
        <h1 class="display-4">Hello<?php if(isset($_SESSION["userID"])){ echo ", ".htmlspecialchars($profile["Fname"]);};?>!</h1>
        <p class="lead">Welcome to the Cloud Airlines Loyalty program home page!</p>
        <hr class="my-4">
        <p class="lead">
            <a class="btn btn-warning btn-lg" href="#" role="button">Learn more</a>
        </p>
    </div>

    

   
    <!-- FOOTER TAG IN ALL PAGES (/body included)-->
    <?php include('templates/footer.php'); ?>

</html>