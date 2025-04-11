<?php
// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$firstname = $lastname = $cphone = $cemail = $ccity = $caddress = $ccountry = $cposition = $cinfo = $cresume = "";
$firstname_err = $lastname_err = $phone_err = $email_err = $city_err = $address_err = $country_err = $position_err = $resume_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate firstname
    if (empty(trim($_POST["firstname"]))) {
        $firstname_err = "Please enter your first name.";
    } else {
        $firstname = trim($_POST["firstname"]);
    }

    // Validate lastname
    if (empty(trim($_POST["lastname"]))) {
        $lastname_err = "Please enter your last name.";
    } else {
        $lastname = trim($_POST["lastname"]);
    }

    // Validate phone
    if (empty(trim($_POST["cphone"]))) {
        $phone_err = "Please enter your phone number.";
    } else {
        $cphone = trim($_POST["cphone"]);
    }

    // Validate email
    if (empty(trim($_POST["cemail"]))) {
        $email_err = "Please enter your email.";
    } else {
        $cemail = trim($_POST["cemail"]);
    }

    // Validate city
    if (empty(trim($_POST["ccity"]))) {
        $city_err = "Please enter your city.";
    } else {
        $ccity = trim($_POST["ccity"]);
    }

    // Validate address
    if (empty(trim($_POST["caddress"]))) {
        $address_err = "Please enter your address.";
    } else {
        $caddress = trim($_POST["caddress"]);
    }

    // Validate country
    if (empty(trim($_POST["ccountry"]))) {
        $country_err = "Please enter your country.";
    } else {
        $ccountry = trim($_POST["ccountry"]);
    }

    // Validate position
    if (empty(trim($_POST["cposition"]))) {
        $position_err = "Please select desired position.";
    } else {
        $cposition = trim($_POST["cposition"]);
    }

    // Validate additional info
    $cinfo = trim($_POST["cinfo"]);

    // Validate uploaded resume
    if ($_FILES["cresume"]["size"] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["cresume"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check file size
        if ($_FILES["cresume"]["size"] > 1000000) {
            $resume_err = "Sorry, your file is too large. Max file size is 1MB.";
            $uploadOk = 0;
        }
        // Allow only pdf file format
        if ($fileType != "pdf") {
            $resume_err = "Sorry, only PDF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // File upload failed
            echo "Sorry, your file was not uploaded.";
        } else {
            // File upload successful
            if (move_uploaded_file($_FILES["cresume"]["tmp_name"], $target_file)) {
                $cresume = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $resume_err = "Please upload your resume in PDF format (Max file size: 1MB).";
    }

    // Check input errors before inserting into database
    if (empty($firstname_err) && empty($lastname_err) && empty($phone_err) && empty($email_err) && empty($city_err) && empty($address_err) && empty($country_err) && empty($position_err) && empty($resume_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO career (firstname, lastname, cphone, cemail, ccity, caddress, ccountry, cposition, cinfo, cresume) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssssss", $param_firstname, $param_lastname, $param_cphone, $param_cemail, $param_ccity, $param_caddress, $param_ccountry, $param_cposition, $param_cinfo, $param_cresume);

            // Set parameters
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_cphone = $cphone;
            $param_cemail = $cemail;
            $param_ccity = $ccity;
            $param_caddress = $caddress;
            $param_ccountry = $ccountry;
            $param_cposition = $cposition;
            $param_cinfo = $cinfo;
            $param_cresume = $cresume;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to success page
                header("location: career.html");
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
