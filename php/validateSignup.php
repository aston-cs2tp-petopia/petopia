<?php
    function validateSignupData($fName, $lName, $email, $pNumber, $username, $password) {
        $errors = [];

        // Validate first name (only letters)
        if (empty($fName) || !preg_match("/^[a-zA-Z ]*$/", $fName)) {
            $errors[] = "First name is required and must contain only letters.";
        }

        // Validate last name (only letters)
        if (empty($lName) || !preg_match("/^[a-zA-Z ]*$/", $lName)) {
            $errors[] = "Last name is required and must contain only letters.";
        }

        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required.";
        }

        // Validate phone number (simple numeric check)
        if (empty($pNumber) || !is_numeric($pNumber)) {
            $errors[] = "Phone number is required and must be numeric.";
        }

        // Validate username
        if (empty($username)) {
            $errors[] = "Username is required.";
        }

        // Validate password
        if (empty($password)) {
            $errors[] = "Password is required.";
        }

        // Add more specific validations as required...

        return $errors;
    }
?>