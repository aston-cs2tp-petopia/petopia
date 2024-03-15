
document.addEventListener("DOMContentLoaded", () => {
    const signup = document.querySelector(".login-bottom strong");
    const login = document.querySelector(".register-bottom strong");

    const loginContainer = document.querySelector(".login-container");
    const signupContainer = document.querySelector(".signup-container");

    login.addEventListener("click", () => {
        loginContainer.classList.add("hide");
        signupContainer.classList.remove("hide");
    })

    signup.addEventListener("click", () => {
        signupContainer.classList.add("hide");
        loginContainer.classList.remove("hide");
    })
})

/*Validations*/
function validateSignupData() {
    var fName = document.getElementById('signup-firstname').value;
    var lName = document.getElementById('signup-lastname').value;
    var email = document.getElementById('signup-email').value;
    var pNumber = document.getElementById('signup-number').value;
    var username = document.getElementById('signup-username').value;
    var password = document.getElementById('signup-password').value;
    
    var errors = [];

    // Validate first name (only letters)
    if (!fName || !/^[a-zA-Z ]*$/.test(fName)) {
        errors.push("Valid first name required.");
    }

    // Validate last name (only letters)
    if (!lName || !/^[a-zA-Z ]*$/.test(lName)) {
        errors.push("Valid last name required.");
    }

    // Validate email
    if (!email || !/^\S+@\S+\.\S+$/.test(email)) {
        errors.push("Valid email is required.");
    }

    // Validate phone number (must be 11 digits and start with 07)
    if (!pNumber || !/^07\d{9}$/.test(pNumber)) {
        errors.push("Phone number is required, must be 11 digits and start with 07.");
    }

    // Validate username
    if (!username) {
        errors.push("Username is required.");
    }

    // Validate password
    if (!password || password.length < 8 || !/[!@#$%^&*(),.?":{}|<>]/g.test(password)) {
        errors.push("Password must be at least 8 characters long and include a special character.");
    }

    // If there are errors, display them and return false
    if (errors.length > 0) {
        alert(errors.join("\n• "));
        return false;
    }

    // If no errors, form is valid
    return true;
}
