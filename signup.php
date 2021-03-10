<!-- PHP THAT HANDLES REQUEST FROM FORM -->
<?php
    session_start();
    
    //config files
    include("config/db_connect.php");
    include("config/user_connect.php");


    //intialize input feilds to empty
    $Fname = $Lname = $phone = $email = $pass = $address1 = $address2 = $city = $province = $postal =  ""; 
    
    //setting modal messages
    $modal = $messageBody = $messageTitle = "";
        
    // CHECK IF THERE IS DATA USING GLOBAL VARAIABLE $_GET ASSOCIATIVE ARRAY = POST METHOD (HIDDIN data method)
        if(isset($_POST['submitbtn'])){ // SERVER-SIDE VALIDATION 
            
            $searchValue = $_POST['phone'];
            $sql = "SELECT phone FROM profiles WHERE phone LIKE '%$searchValue%'";
            $result = mysqli_query($connection, $sql);

            if (mysqli_num_rows($result) >! 0){
                // ACTIVATE ERROR MODAL!
                $messageTitle = "Phone Number Error!";
                $messageBody = "It seems as the number submited in the form is already connected to a pre-existing account! Please try re-submiting the form with a valid phone number!";
                $modal= "activated";

            } else {
                //check for errors in $error array and save to data base          
                $Fname = mysqli_real_escape_string($connection, $_POST['Fname']);
                $Lname = mysqli_real_escape_string($connection, $_POST['Lname']);
                $phone = mysqli_real_escape_string($connection, $_POST['phone']);
                $email = mysqli_real_escape_string($connection, $_POST['email']);
                $pass = mysqli_real_escape_string($connection, $_POST['pass']);
                $address1 = mysqli_real_escape_string($connection, $_POST['address1']);
                $address2 = mysqli_real_escape_string($connection, $_POST['address2']);
                $city = mysqli_real_escape_string($connection, $_POST['city']);
                $province = mysqli_real_escape_string($connection, $_POST['province']);
                $postal = mysqli_real_escape_string($connection, $_POST['postal']);

                //create sql string inserting into profile table 
                $sql = "INSERT INTO profiles(Fname,Lname,phone,email,pass,address1,address2,city,province,postal) VALUES('$Fname','$Lname','$phone','$email','$pass','$address1','$address2','$city','$province','$postal')";

                //save sql to database and check
                if(mysqli_query($connection, $sql)){
                    //sucsess modal and redirect to home
                    $MessageTitle = "Thank you!";
                    $MessageBody = "Thank you for signing up with Cloud Airlines! We apreaciate your trust in us! To welcome you properly, we have added 500 points to your accont that you can later redeem for miles!";
                    $modal= "activated";

                    $_SESSION['userID'] = $profile['id'];
         
                    header("Location: index.php");

                } else { 
                    //error
                    echo "query error :" .mysqli_error($connection);
                };            
            };
         
        };

    //end of Uploading to Database "Profile"     
    mysqli_close($connection);
?>



<!DOCTYPE html>
<html lang="en">
    <?php include("templates/header.php") ?>

    <br>
    <!-- Sign in form -->
    <div class="container">
            <div class="card border border-secondary" style="width:85%; margin:auto !important;">
            <div class="card-header text-dark bg-warning"><h4>Sign Up</h4></div>
                <div class="card-body">
                
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="validationCustom01" class="form-label">First Name</label>
                            <input type="text" class="form-control" name="Fname" id="validationCustom01" value="<?php echo htmlspecialchars($Fname)?>" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="validationCustom02" class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="Lname" id="validationCustom02" value="<?php echo htmlspecialchars($Lname)?>" required>
                        </div>

                        <div class="col-md-4">
                            <label for="validationCustom03" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" id="validationCustom03" placeholder="-----------" pattern="([0-9]{3})[0-9]{3}[0-9]{4}" value="<?php echo htmlspecialchars($phone)?>" required>
                            <div class="invalid-feedback">
                               Please remove spaces and dashes
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="inputEmail4" class="form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmail4" placeholder="Email" name="email" value= "<?php echo htmlspecialchars($email) ?>" required>
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>    
                        </div>

                        <div class=" col-md-6">
                            <label for="inputPassword4" class="form-label">Password</label>
                            <input type="password" class="form-control" id="inputPassword4" placeholder="Password" name="pass" value= "<?php echo htmlspecialchars($pass) ?>" required>
                        </div>
                    </div>

                    <br>

                    <div class="form-group">
                        <label for="inputAddress" class="form-label" >Address</label>
                        <input type="text" class="form-control" id="inputAddress" name="address1" placeholder="1234 Main St" value= "<?php echo htmlspecialchars($address1) ?>" required>
                        <div class="invalid-feedback">
                            Please provide a valid address.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputAddress2" class="form-label" >Address 2 </label>
                        <input type="text" class="form-control" id="inputAddress2" name="address2" placeholder="Apartment, studio, or floor (If nothing, please type 'N/A'" value= "<?php echo htmlspecialchars($address2) ?>" required>
                        <div class="invalid-feedback">
                            Please provide a valid address. (If nothing, please type 'N/A')
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="inputCity" class="form-label">City</label>
                            <input type="text" class="form-control" id="inputCity" name="city" value= "<?php echo htmlspecialchars($city) ?>" required>
                            <div class="invalid-feedback">
                                Please provide a city name.
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="inputState" class="form-label">Province</label>
                            <select id="inputState" class="form-control" name="province" value="<?php echo htmlspecialchars($province) ?>" required>
                                <option selected></option>
                                <option>AB</option>
                                <option>BC</option>
                                <option>ON</option>
                                <option>SK</option>
                                <option>NU</option>
                                <option>NS</option>
                                <option>NB</option>
                                <option>MN</option>
                                <option>PEI</option>
                                <option>NF</option>
                                <option>QB</option>
                                <option>NWT</option>
                                <option>YU</option>
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid province.
                            </div>
                        </div>

                        <br>

                        <div class="form-group col-md-3">
                            <label for="inputZip" class="form-label" >Postal Code</label>
                            <input type="text" class="form-control" id="inputZip" name="postal" value="<?php echo htmlspecialchars($postal) ?>" required>
                            <div class="invalid-feedback">
                                Please provide a valid zip.
                            </div>
                        </div>

                    </div>
                    <button type="submit" name="submitbtn" value="submit" class="btn btn-outline-dark">Submit</button>
                    
                </form>
                </div>
            </div>
        </div>
        <br><br><br>


        <!-- ERROR MODAL CODE!!! -->
        <div class="modal hide fade" id="messageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel"><?php echo $messageTitle ?> </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> 
                    </div>
                    <div class="modal-body">
                        <p><?php echo $messageBody ?></p>                     
                    </div>    

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        
    <?php include("templates/footer.php") ?>

    
    <?php if(isset($_POST['submitbtn'])) { if($modal = "activated") { ?>
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('messageModal'), {
                    keyboard: false
                });
            myModal.show()
        </script>
    <?php } } ?>

<br>
</html>