<?php

//This page is responsible for server side validation of data entered on the sign up page. Completed using php.
    function validateSignupData($fName, $lName, $email, $pNumber, $username, $password = null) {
        $errors = [];

        //Validate first name (only letters)
        if (empty($fName) || !preg_match("/^[a-zA-Z ]*$/", $fName)) {
            $errors[] = "• Please enter a valid first name";
        }
        //Ensures that the last name isn't too large
        elseif (strlen($fName) > 50) {
            $errors[] = '• First name must be less than 50 characters';
        }


        //Validate last name (only letters)
        if (empty($lName) || !preg_match("/^[a-zA-Z ]*$/", $lName)) {
            $errors[] = "• Please enter a valid last name";
        }
        //Ensures that the last name isn't too large
        elseif (strlen($lName) > 50) {
            $errors[] = '• Last name must be less than 50 characters';
        }


        //Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "• Valid email is required.";
        }


        //Validate phone number (numeric check)
        if (empty($pNumber)) {
            $errors[] = "• Phone number is required.";
        } elseif (!preg_match("/^07\d{9}$/", $pNumber)) {
            // Checks if the phone number starts with '07' and is followed by 9 more digits, making it 11 digits in total
            $errors[] = "• Phone number must start with '07' and be 11 digits long.";
        }

        // Validate username
        if (empty($username)) {
            $errors[] = "• Username is required";
        }
        //Ensures that the username isn't too large
        elseif (strlen($username) > 50) {
            $errors[] = '• Username must be less than 50 characters';
        }
        
        // Validate password
        if ($password !== null && (empty($password) || !preg_match("/^(?=.*\d)[A-Za-z\d!@#$%^&*()-_+=]{8,}$/", $password))) {
            $errors[] = "• Password must be at least 8 characters long and include at least one special character";
        }
        

        return $errors;
    }
?>