<!-- //MySQLi approcach, using user we created and database -->
<!-- connect to data base -->
<?php 
    $connection = mysqli_connect("localhost", "hala", "halaspizza", "cloud_air_profiles");

    if(!$connection){
        echo "connection error: ". mysqli_connect_error();
    }

?>