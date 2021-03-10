<?php

// Start session
session_start();

include("config/db_connect.php");
include("config/user_connect.php");

//changing Profile
$changesTo = $inputedChange = "";
if (isset($_SESSION["userID"])){
    if (isset($_POST['submitChange']) && isset($_POST['changecheckbox'])) {
        $inputedChange = $_POST['inputedChange'];
    
        if ($_POST['changecheckbox'] == 'changingFname'){
            echo "<br>"."changing Fname to $inputedChange";
            //update Fname
            mysqli_query($connection, "UPDATE profiles SET Fname = '$inputedChange' WHERE id = $_SESSION[userID]");
            echo "<br>"."CHANGED.";
            echo $profile["Fname"];

        } elseif ($_POST['changecheckbox'] == 'changingLname') {
            echo "<br>"."changing Lname to $inputedChange";
            //update Lname
            mysqli_query($connection, "UPDATE profiles SET Lname = '$inputedChange' WHERE id = $_SESSION[userID]");
            echo "<br>"."CHANGED.";


        } elseif ($_POST['changecheckbox'] == 'changingPhone') {
            echo "<br>"."changing phone to $inputedChange";
            //update phone
            mysqli_query($connection, "UPDATE profiles SET phone = '$inputedChange' WHERE id = $_SESSION[userID]");
            echo "<br>"."CHANGED.";

        } elseif ($_POST['changecheckbox'] == 'changingProvince') {
            echo "<br>"."changing province to $inputedChange";
            //update province
            mysqli_query($connection, "UPDATE profiles SET province = '$inputedChange' WHERE id = $_SESSION[userID]");
            echo "<br>"."CHANGED.";

        }elseif ($_POST['changecheckbox'] == 'changingCity') {
            echo "<br>"."changing city to $inputedChange";
            //update City
            mysqli_query($connection, "UPDATE profiles SET city = '$inputedChange' WHERE id = $_SESSION[userID]");
            echo "<br>"."CHANGED.";

        } else {
            echo "<br>"."no change is being made";
        }
    }
}


//deleting profile 
if (isset($_POST['deleteProfileBtn'])) {
    $searchValue = $_POST['checkValidPhone'] ;
    $sql = "SELECT id FROM profiles WHERE phone LIKE '%$searchValue%'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0){
        // DELETE ACCOUNT BY FETHCHED ID
        
        $accountToDelete = "DELETE FROM profiles WHERE phone LIKE '$searchValue'";
        $deleteingAccount = mysqli_query($connection, $accountToDelete);

        // set all values into " " to clear for next login!
        $Fname = $Lname = $phone = $email = $pass = $address1 = $address2 = $city = $province = $postal =  ""; 


        //delete all flight information (LATER)
        echo "Account with the phone number $searchValue has been deleted, no information will be displayed";

    } else {
        echo "Could not delete account; account with that number does not exist";
    }
}

// point calculation and display
if(isset($_SESSION["userID"])){
    $unprocessedPoints = htmlspecialchars($profile["points"]);
    $points = (int)$unprocessedPoints;
    $displayedpointpercent = $points / 10000 * 100;
}


//booking random Flight 
if(isset($_POST['flightBookingConfirmationBtn']) && isset($_SESSION['userID'])){ // SERVER-SIDE VALIDATION 
 
    //update random points
    $randPointArray = array(100,200,300,400,500);
    $randPointIndex = array_rand($randPointArray, 1);
    $randPoint = $randPointArray[$randPointIndex];
    $newpointAmount = htmlspecialchars($profile["points"]) + $randPoint;
    mysqli_query($connection,"UPDATE profiles SET points = $newpointAmount WHERE id = $_SESSION[userID]");
               
    
    // Setting variables to insert into database
    //to Variable
        $toArray = array("Sydney, Australia", "Tokyo, Japan", "Paris, France", "Washington D.C, US", "Vienna, Austria");
        $rand_to = array_rand($toArray, 1);
        $to = $toArray[$rand_to];
    
    //from & Passenger Varible
        $passenger = $profile["Fname"]." ". $profile["Lname"];
        $from = $profile["city"].",". $profile["province"];

    //flight Num, date, Borading time, Boarding sequence, seat
        $flightNum = rand (10000 , 99999);
        $date = rand(1,12)."/".rand(1,31)."/".rand(2021, 2050);
        $time = rand(1,24).":".rand(01,59);
        $boardingSequence = rand(1,5);
        $seat =  chr(rand(65,90)).rand(1,40);

    //insering random flight
    $newTicketSql = "INSERT INTO tickets(passengerId,flightNum,fromLocation,toLocation,ticketDate,boardingTime,seat,boardingSequence) VALUES('$_SESSION[userID]','$flightNum','$from','$to', '$date', '$time', '$seat', '$boardingSequence')";
    
    //save sql to database and check
    if(mysqli_query($connection, $newTicketSql)){
        //sucsess modal and update
        echo "<br>"."Booking Flight Sucsessfull! Ticket has been added to database";
        //reset to not book more tickets
        header("Location: profile.php");

    } else { 
        //error
        echo "query error :" .mysqli_error($connection);
    };            

};
   


//Fetch Ticket information to display
if (isset($_SESSION['userID'])){
    //ADD CHEKIING SECOND DATABASE BY ID AND OUTPUTTING CARDS FOR FLIGHT INFOMRATIOON
    $sql = "SELECT * FROM tickets WHERE passengerId = $_SESSION[userID]";
    $tickets =  mysqli_query($connection, $sql);

    if (mysqli_num_rows($tickets) == 0 && isset($_SESSION["userID"])){
        echo "<br>"."no tickets to display";
        $ticketsPresent = "false";

    } else{
       
        $ticketsPresent = "true";
        echo "<br>"."tickets displayed";
    }

} else {
    $ticketsPresent = "false";
    echo "<br>"."No tickets will be displayed as there is NO account logged in";
}

session_write_close();
mysqli_close($connection);
?>



<!DOCTYPE html>
<html lang="en">
<?php include("templates/header.php");?>
<div class="container">

    <div class="container py-5">
        <div class="row">

            <div class="col-xl-9 col-lg-7 mb-5">
                <div class="bg-white rounded-lg p-5 shadow">
                    <div class="row">
                        <h1>Information/Profile</h1>
                        <div class="text-secondary text-right">Id #: <?php if(isset($_SESSION["userID"])){echo htmlspecialchars($profile["id"]);};?></div>
                    </div>

                    <br>

                    <div class="text">First Name: <span class="font-weight-bold"><?php if(isset($_SESSION["userID"])){echo htmlspecialchars($profile["Fname"]);}; ?></span> </div>
                    <div class="text">Last Name: <span class="font-weight-bold"><?php if(isset($_SESSION["userID"])){echo htmlspecialchars($profile["Lname"]);}; ?> </span> </div>
                    <div class="text">Phone Number: <span class="font-weight-bold"><?php if(isset($_SESSION["userID"])){echo htmlspecialchars($profile["phone"]);}; ?> </span> </div>
                    <div class="text">Email: <span class="font-weight-bold"><?php if(isset($_SESSION["userID"])){echo htmlspecialchars($profile["email"]);}; ?> </span> </div>
                    <div class="text">Address: <span class="font-weight-bold"><?php if(isset($_SESSION["userID"])){echo htmlspecialchars($profile["address1"]).",". htmlspecialchars($profile["address2"]);}; ?> </span> </div>
                    <div class="text">Postal Code: <span class="font-weight-bold"><?php if(isset($_SESSION["userID"])){echo htmlspecialchars($profile["postal"]);}; ?> </span> </div>
                    <div class="text">Location (City, Province): <span class="font-weight-bold"><?php if(isset($_SESSION["userID"])){echo htmlspecialchars($profile["city"]).",". htmlspecialchars($profile["province"]);}; ?></span> </div>

                    <br>

                    <div class="text-right">
                        <!-- edit button -->
                        <button class="btn btn-outline-warning btn-lg" type="button" data-toggle="modal" data-target="#editProfileModal">
                            <ion-icon name="pencil"></ion-icon>
                        </button>

                        <!-- delete account -->
                        <button class="btn btn-outline-secondary btn-lg" type="button" name="deleteProfileBtn"  data-toggle="modal" data-target="#deleteProfileModal">
                            <ion-icon name="trash"></ion-icon>
                        </button>

                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="mb-4">
                    <div class="bg-white rounded-lg p-5 shadow">
                        <h2 class="h5 font-weight-bold text-center mb-4">Point Progress</h2>
                        <h5 class="text-center"><?php if(isset($_SESSION["userID"])){ echo $displayedpointpercent;};?>%</h5>
                        <!-- Progress bar 1 -->
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemax="10000" style="width:<?php echo $displayedpointpercent; ?>%"></div>
                        </div>
                        <h5 class="text-center"><?php if(isset($_SESSION["userID"])){echo htmlspecialchars($profile["points"]);}; ?>/10,000</h5>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4 shadow text-center" >
                    <button class="btn btn-warning btn-lg" name="bookflightBtn" type="button" data-toggle="modal" data-target="#bookFlightModal" >BOOK A FLIGHT!</button>
                </div>

            </div>

            <!-- ROW 2 -->

            <!-- Calendar -->
            <div class="col-xl-3 col-lg-6 mb-4">
                <div class="bg-white rounded-lg p-5 shadow">
                    <h2 class="h5 font-weight-bold text-center mb-4">Calendar</h2>
                </div>
            </div>

            <!-- upcoming Flights/Tickets FROM SECOND DATABASE -->
            <div class="col-xl-9 col-lg-7 mb-5">
                
                <div class="bg-white rounded-lg p-5 shadow">
                    <h1><ion-icon name="airplane"></ion-icon> Upcoming Flights </h1>

                    <div>
                        <?php if($ticketsPresent == "true"){ 
                            // $ticketsWithId = mysqli_query($connection, "SELECT * FROM tickets WHERE passengerId = $_SESSION[userID]");
                            for($i=0 ; $i < mysqli_num_rows($tickets); $i++){
                                $ticket = mysqli_fetch_array($tickets, MYSQLI_ASSOC); ?>
                                
                                <!-- DISPLAY INDIVIDUAL TICKETS ASSOCIATED WITH ID -->
                                    <div class="row rounded-lg shadow m-3"> 
                                        <!-- div 1 for flight information -->
                                        <div class="col-sm-8 rounded-lg p-5" style ="background-image: url('img/grayWorldMap.png');  background-position: center ; background-repeat: no-repeat; background-size: contain;">
                                            <h5><strong>Passenger: </strong><?php echo htmlspecialchars($profile["Fname"])." ".htmlspecialchars($profile["Lname"]); ?></h6>
                                            <br>
                                            <h6><ion-icon name="ticket"></ion-icon><strong> Flight Number: </strong><?php echo htmlspecialchars($ticket["flightNum"]);?> </h6>
                                            <h6><ion-icon name="home"></ion-icon><strong> From: </strong><?php echo htmlspecialchars($ticket["fromLocation"]); ?></h6>
                                            <h6><ion-icon name="globe"></ion-icon><strong> To: </strong><?php echo htmlspecialchars($ticket["toLocation"]); ?></h6>
                                            <h6><ion-icon name="calendar"></ion-icon><strong> Date: </strong><?php echo htmlspecialchars($ticket["ticketDate"]); ?></h6>
                                        </div>

                                        <!-- Div 2 Small -->
                                        <div class="bg-warning col-sm-4 rounded-lg p-5" >
                                            <h6><ion-icon name="time"></ion-icon><strong>Boarding Time: </strong><?php echo htmlspecialchars($ticket["boardingTime"]); ?></h6>
                                            <h6><strong>Boarding Sequence: </strong><?php echo htmlspecialchars($ticket["boardingSequence"]);?></h6>
                                            <h6><strong> Seat: </strong> <?php echo htmlspecialchars($ticket["seat"]); ?></h6>
                                            <br>
                                            <h6><strong>Date Booked: </strong><?php echo "<br>".htmlspecialchars($ticket["booked_at"]); ?></h6>
                                        </div>
                                    </div>
                        <?php }} ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>





<!-- Edit Profile MODAL CODE!!! -->
<div class="modal hide fade" id="editProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit/Update Profile</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <!-- Display Profile -->
                    <div class="container">
                        <div><span class="font-weight-bold">What would you like to change?</span></div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="changecheckbox" value="changingFname">
                            <label class="form-check-label" for="inlineRadio1">First Name</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="changecheckbox" value="changingLname" >
                            <label class="form-check-label" for="inlineRadio2">Last Name</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="changecheckbox" value="changingEmail">
                            <label class="form-check-label" for="inlineRadio3">Email</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="changecheckbox" value="changingPhone">
                            <label class="form-check-label" for="inlineRadio3">Phone Number</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="changecheckbox" value="changingProvince">
                            <label class="form-check-label" for="inlineRadio3">Province</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="changecheckbox" value="changingCity">
                            <label class="form-check-label" for="inlineRadio3">City (AB,QB etc.)</label>
                        </div>
                    </div>
            
            <br>
            

            <div class="input-group mb-3">
                <input type="text" name="inputedChange" value="<?php echo htmlspecialchars($inputedChange) ?>" class="form-control" placeholder="Enter Change Here" required>
                <div class="input-group-append">
                    <button class="btn btn-outline-warning" name="submitChange" type="submit" value="submit">Submit Change</button>
                </div>
            </div>
            </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE PROFILE MODAL-->
<div class="modal hide fade" id="deleteProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Delete Profile</h4>
            </div>

            <div class="modal-body">
                <h5>Are you sure you would like to delete your loyalty account?</h5>
                <h6>If you wish to continoue with this process, all your points and ablity to book tickets through this account will be dissable and not acssable</h6>
        


                <form action=" <?php echo $_SERVER['PHP_SELF'] ?> " method="POST">
                    <!-- confimartion of account to be deleted by phone number -->
                    <label class="form-label">Phone Number</label>
                    <input  class="form-control" name="checkValidPhone" placeholder="-----------" pattern="([0-9]{3})[0-9]{3}[0-9]{4}">

                    <!-- delete btn -->
                    <div class="text-right">
                    <button name="deleteProfileBtn" type="submit" value="submit" class="btn btn-warning text-right">Delete Profile</button>
                    </div>
                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>



<!-- BOOK FLIGHT CONFERMATION MODAL-->
<div class="modal hide fade" id="bookFlightModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Book a random flight?</h4>
            </div>

            <div class="modal-body">
                <h7>The flight will take off from your home town indicated from your profile and will generate a random flight somewhere across the world! You will even recive points from a random gnerator up to 500 points! </h7>
                <br>
                <h7>Click the button below to confirm!</h7>
            </div>
                
            <div class="modal-body text-center">
                <form action=" <?php echo $_SERVER['PHP_SELF'] ?>" method="POST" >
                    <!-- confimartion of booking ticket -->
                    <button type="submit" value="submit" class="btn btn-warning text-right" name="flightBookingConfirmationBtn"><strong>CONFIRM FLIGHT</strong></button>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER TAG IN ALL PAGES (/body included)-->
<?php 
    //closing session
    session_write_close();
    include('templates/footer.php'); 
?>

</html>