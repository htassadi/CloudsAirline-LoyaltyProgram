<?php 
    // Start session
    session_start();

    include("config/db_connect.php");
    include("config/user_connect.php");



    $missingAccount= "";

    // filter though data base and find account, if not avalible, create modal that shows error and redirects to sign up page if wanted
    if (isset($_POST['submitlogin'])){ // SERVER-SIDE VALIDATION
    
        $sql = "SELECT * FROM profiles WHERE phone = $_POST[phone] ";
        $result = mysqli_query($connection, $sql);
        $profile = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
        if(mysqli_num_rows($result) > 0){
            //change all set names and information based on loged in person
            echo "logged in";
            $missingAccount = "false";
            $_SESSION['userID'] = $profile['id'];
            header("Location: index.php");
            
        } else {
            $missingAccount = "true";
            echo "error";   
        }

    };

    mysqli_close($connection);
    session_write_close();

?>



<!DOCTYPE html>
<html lang="en">
    <?php include("templates/header.php") ?>

    <!-- alerts -->
    <?php if(isset($_POST['submitlogin'])) { if($missingAccount = "true");{ ?>
            <div class="alert alert-info alert-dismissible fade show " role="alert">
                <h4 class="alert-heading">ALERT!</h4>
                <p>A pre-existing account with this information is not found, if you DO NOT have a loyaly account with us please direct yourself tot he sign up page! A 500 point bonus will be awarded when a new account is set up!</p>
                    <hr>
                <p class="mb-0">If you DO have an account with us please dissmis this alert and try again!</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
    <?php } } ?>


    <br>
    <!-- login form -->
    <div class="container">
        <div class="card border border-secondary" style="width:82.75%; margin:auto !important;">
            <div class="card-header text-dark bg-warning"><h4>Login<h4> </div>
            <div class="card-body">
                <form class="g-3 needs-validation" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="validationCustom01" class="form-label">First name</label>
                            <input type="text" class="form-control" id="validationCustom01" value="" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="validationCustom02" class="form-label">Last name</label>
                            <input type="text" class="form-control" id="validationCustom02" value="" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="validationCustom03" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="validationCustom03" placeholder="-----------" pattern="([0-9]{3})[0-9]{3}[0-9]{4}" value="" name="phone" required>
                            <div class="invalid-feedback">
                                Please follow this format: --- --- -----
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="col-md-3">
                        <button type="submit" name="submitlogin" value="submit" class="btn btn-outline-dark">Submit</button>
                    </div>
                </form>
            <div>
        </div>
    </div>

    </div>
    <br>

    <div class="container">
        <div class="card border border-secondary" style="width:50%; margin:auto !important;">
        <div class="card-body">
            <h6> Don't have a loyalty Account? <a href="signup.php" class="text-primary">Sign up</a></h6>
        </div>
        </div>
    </div>
    <br>

    </div>

    <?php include("templates/footer.php") ?>
</html>