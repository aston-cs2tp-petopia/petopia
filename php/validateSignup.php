<?php

//This page is responsible for server side validation of data entered on the sign up page. Completed using php.
    function validateSignupData($fName, $lName, $email, $pNumber, $username, $password) {
        $errors = [];

        // Validate first name (only letters)
        if (empty($fName) || !preg_match("/^[a-zA-Z ]*$/", $fName)) {
            $errors[] = "First name is required and must contain only letters.";
        }
        //Ensures that the last name isn't too large
        elseif (strlen($fName) > 50) {
            $errors[] = 'Last name must be less than 50 characters';
        }


        // Validate last name (only letters)
        if (empty($lName) || !preg_match("/^[a-zA-Z ]*$/", $lName)) {
            $errors[] = "Last name is required and must contain only letters.";
        }
        //Ensures that the last name isn't too large
        elseif (strlen($lName) > 50) {
            $errors[] = 'Last name must be less than 50 characters';
        }


        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required.";
        }


        // Validate phone number (simple numeric check)
        if (empty($pNumber) || !is_numeric($pNumber)) {
            $errors[] = "Phone number is required and must be numeric.";
        }
        //Ensures that the phone number starts with 0 , followed by 9-10 digits 
        //(This results in a total of 10-11 digits, which is the length of a typical uk phone number.)
        else if (!preg_match("/^0[0-9]{9,10}$/", $pNumber))


        // Validate username
        if (empty($username)) {
            $errors[] = "Username is required.";
        }
        //Ensures that the username isn't too large
        elseif (strlen($username) > 50) {
            $errors[] = 'Username must be less than 50 characters';
        }
        
        // Validate password
        if (empty($password)) {
            $errors[] = "Password is required.";
        }
        //ensures that the password must have at least one uppercase letter (first bracket), one digit (second bracket), one special character (third bracket)
        //As well as ensuring that the password may have letters/digits/characters throughout, with a minimum length of 6 and a maximum length of 50
        else if(!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()-_+=])[A-Za-z0-9!@#$%^&*()-_+=]{6,}$/",$password))

        // Add more specific validations as required...

        //Validate postcode
        

        return $errors;
    }
?>