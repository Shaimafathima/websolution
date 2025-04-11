<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$sname = $semail = $sphone = $smessage = "";
$sname_err = $semail_err = $sphone_err = $smessage_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["sname"]))) {
        $sname_err = "Please enter your name.";
    } else {
        $sname = trim($_POST["sname"]);
    }

    // Validate email
    if (empty(trim($_POST["semail"]))) {
        $semail_err = "Please enter your email.";
    } else {
        $semail = trim($_POST["semail"]);
    }

    // Validate phone
    if (empty(trim($_POST["sphone"]))) {
        $sphone_err = "Please enter your phone number.";
    } else {
        $sphone = trim($_POST["sphone"]);
    }

    // Validate message
    if (empty(trim($_POST["smessage"]))) {
        $smessage_err = "Please enter your message.";
    } else {
        $smessage = trim($_POST["smessage"]);
    }

    // Check input errors before inserting into database
    if (empty($sname_err) && empty($semail_err) && empty($sphone_err) && empty($smessage_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO service (sname, semail, sphone, smessage) VALUES (?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssss", $param_sname, $param_semail, $param_sphone, $param_smessage);

            // Set parameters
            $param_sname = $sname;
            $param_semail = $semail;
            $param_sphone = $sphone;
            $param_smessage = $smessage;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Close statement
                $stmt->close();
                
                // Redirect to success page
                header("location: website.html");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>
