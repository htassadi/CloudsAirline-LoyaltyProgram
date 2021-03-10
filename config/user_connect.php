<?php  

   //check if account is logged in at moment , if not, display nothing 
    if(!isset($_SESSION["userID"])){
        // Haven't log in
        echo "You haven't log in, no information will be displayed";

    } else{
        // Logged in, setting up the most recent profile and name across all pages

        //write query for all profiles, ordered by date
        $sql = "SELECT * FROM profiles WHERE id = $_SESSION[userID] ";
        
        //make qurey  and get resluts 
        $result = mysqli_query($connection, $sql);

        $profile = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
        //free Result from memory
        mysqli_free_result($result);
        
        print_r($profile);

        echo "You are logged in! Your information is diplayed";
        $_SESSION["userID"] = $profile['id'];
        
        echo $_SESSION["userID"];

    }
?>