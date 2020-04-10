<?php

require_once(dirname(__FILE__) . '../controller/db.php');
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>GetMi | Rider Registration - Step 1</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script type="text/javascript" src="../assets/js/riderRegUI.js"></script>
    <link rel = "stylesheet" type = "text/css" href = "../assets/css/rider-reg.css" />
</head>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['register'])) { //user logging in

        require 'rider_reg_submit.php';

    }

}
?>

<body>

<!-- MultiStep Form -->
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <form id="msform">
            <!-- progressbar -->
            <ul id="progressbar">
                <li class="active">Personal Details</li>
                <li>Social Profiles</li>
                <li>Account Setup</li>
            </ul>
            <!-- fieldsets -->
            <fieldset>
                <h2 class="fs-title">Personal Details</h2>
                <h3 class="fs-subtitle">Tell us about you</h3>
                <input type="email" name="email" placeholder="Email Address (you@domain.com)"/>
                <input type="text" name="fname" placeholder="First Name"/>
                <input type="text" name="lname" placeholder="Last Name"/>
                <input type="text" name="dob" placeholder="Date of Birth (DD/MM/YYYY)"/>
                <input type="text" name="phone" placeholder="Phone"/>
                <input type="text" name="address" placeholder="Address (Streeet, City, State, Postcode)"/>
                <input type="text" name="emergencyname" placeholder="Emergency Contact's Name"/>
                <input type="text" name="emergencyphone" placeholder="Emergency Contact's Phone"/>
                <input type="button" name="next" class="next action-button" value="Next"/>
            </fieldset>
            <fieldset>
                <h2 class="fs-title">GetMi Account Details</h2>
                <h3 class="fs-subtitle">Rider preferences and application details</h3>
                <input type="text" name="license" placeholder="License No"/>
                <input type="button" id="uploadfilefakebutton" value="Click Here to upload your Drivers License"/>
                <input type="file" id="licensefile" name="licensefile" style="display:none;" />
                <input type="text" name="vehicletype" placeholder="Vehicle Type (Bicycle, car, electric boke, etc.)"/>
                <input type="text" name="Vehiclemake" placeholder="Vehicle Make (Toyota, Mercedes, etc.)"/>
                <input type="text" name="Vehiclecolor" placeholder="Vehicle Color (Black, White, Rainbow etc.)"/>
                <input type="text" name="Vehicleregistration" placeholder="Vehicle Registration"/>
                <input type="text" name="riderbank" placeholder="Bank Account to get paid"/>
                <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                <input type="button" name="next" class="next action-button" value="Next"/>
            </fieldset>
            <fieldset>
                <h2 class="fs-title">Create your account</h2>
                <h3 class="fs-subtitle">Give us your consent and final preferences to process your application</h3>

                <input class="label-checkbox" id="label-consent-idcheck" type="button" value="I am giving consent to conduct a background check"/>
                <input class="label-checkbox" id="label-ok-alcohol" type="button" value="I am ok to handle and deliver alcohol"/>
                <input class="label-checkbox" id="label-ok-tobaco" type="button" value="I am ok to handle and deliver tobaco"/>

                <input type="password" placeholder="Enter password" id="password" name="password">

                <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                <input type="submit" name="register" class="submit action-button" value="Submit & Create Account"/>
                
                <!-- Ford input elements that store consent and checkbox based preferences from the above 'label-checkbox' group -->
                <input class="checkbox-checkbox" type="checkbox" id="consent-idcheck" style="display:none;"/>
                <input class="checkbox-checkbox" type="checkbox" id="ok-alcohol" style="display:none;"/>
                <input class="checkbox-checkbox" type="checkbox" id="ok-tobaco" style="display:none;"/>

            </fieldset>
        </form>
    </div>
</div>
<!-- /.MultiStep Form --> 

</body>

</html>