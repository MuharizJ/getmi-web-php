<?php

$regDebug = false;

if ($regDebug)
    echo "its rider_reg_submit.php start...<br />";

require_once('../controller/db.php');

// MJ: Set session variables to be used on profile.php page
$_SESSION['email'] = $_POST['email'];
$_SESSION['fname'] = $_POST['fname'];
$_SESSION['lname'] = $_POST['lname'];

//MJ: Escape all $_POST variables to protect against SQL injections
$first_name = $mysqli->escape_string($_POST['fname']);
$last_name = $mysqli->escape_string($_POST['lname']);
$email = $mysqli->escape_string($_POST['email']);
$password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
$dob = $mysqli->escape_string($_POST['dob']);
$phone = $mysqli->escape_string($_POST['phone']);

// Address is hardcoded until Google Maps integration
$address_street = '2 Shirley Court';
$address_city = 'Point Cook';
$address_state = 'Victoria';
$address_postcode = '3030';

//MJ: Check if user with that email already exists
$result = $mysqli->query("SELECT * FROM users WHERE email='$email'") or die($mysqli->error);

//MJ: We know user email exists if the rows returned are more than 0
if ($result->num_rows > 0) {
    if ($regDebug)
        echo "<p>Already existing user with this email address</p>";

    $_SESSION['message'] = 'User with this email already exists!';
    header("location: error.php");

} 
else { // MJ:  Email doesn't already exist in a database, proceed...

    //MJ: active is 0 by DEFAULT (for the purpose of the demo im activating without email verification)
    $sql = "INSERT INTO users (first_name, last_name, email, password, dob, phone, address_street, address_city, address_state, address_postcode) "
        . "VALUES ('$first_name','$last_name','$email','$password', '$dob', '$phone', '$address_street', '$address_city', '$address_state', '$address_postcode')";

    //TC: Add user to the database
    if ($mysqli->query($sql)) {
            if ($regDebug) //AS: TBD is this logic needed ?
                echo "user successfully inserted into db.";

        file_put_contents("php://stderr", "user created");

        $_SESSION['active'] = 1;
        $_SESSION['logged_in'] = true; // So we know the user has logged in
        $_SESSION['message'] =  "Great! Your registration is complete! You may receive a confirmation link via email sent to $email.";

        header("location: " . "rider_login.html");
    } 
    else {
        echo "insert user failed; connect error: " . $mysqli->connect_error . "; other error: " . $mysqli->error . "<br />";
        $_SESSION['message'] = 'Registration failed!';
        header("location: error.php");
    }

}