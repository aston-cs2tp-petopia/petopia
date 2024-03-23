
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
    var hAddress = document.getElementById('signup-homeAddress').value;
    var postcode = document.getElementById('signup-postcode').value;
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

    if (!hAddress || /[!@#$%^&*().?":{}|<>_]]/.test(hAddress)) {
        errors.push("Home Address is required.");
    }

    if (!postcode || !/^[A-Z]{1,2}[0-9]{1,2}[A-Z]{0,1} ? [0-9][A-Z]{2}$/i.test(postcode)) {
        errors.push("Postcode is required, must contain outcode and incode.");
    }

    // Validate username
    if (!username) {
        errors.push("Username is required.");
    }

    // Validate password
    if (!password || password.length < 8 || !/[!@#$%^&*(),.?":{}|<>_]/g.test(password)) {alert
        errors.push("Password must be at least 8 characters long and include a special character.");
    }

    // If there are errors, display them and return false
    if (errors.length > 0) {
        jsAlert(errors, false, 5000);
        return false;
    }

    

    // If no errors, form is valid
    return true;
}

/*Alerts Function*/
function jsAlert(errors, success, duration) {
    var backgroundColor = success ? '#d4edda' : '#f8d7da';
    var textColor = success ? '#155724' : '#721c24';
    var borderColor = success ? '#c3e6cb' : '#f5c6cb';

    var messageDiv = document.createElement('div');
    var closeBtn = document.createElement('span');
    closeBtn.innerHTML = '&times;';
    closeBtn.style.position = 'absolute';
    closeBtn.style.top = '0';
    closeBtn.style.right = '10px';
    closeBtn.style.fontWeight = 'bold';
    closeBtn.style.fontSize = '20px';
    closeBtn.style.cursor = 'pointer';

    var messageList = document.createElement('ul');
    errors.forEach(function(error) {
        var li = document.createElement('li');
        li.textContent = error;
        messageList.appendChild(li);
    });

    messageDiv.appendChild(messageList);
    messageDiv.appendChild(closeBtn);
    messageDiv.style.position = 'fixed';
    messageDiv.style.top = '50%';
    messageDiv.style.left = '50%';
    messageDiv.style.transform = 'translate(-50%, -50%)';
    messageDiv.style.backgroundColor = backgroundColor;
    messageDiv.style.color = textColor;
    messageDiv.style.padding = '20px';
    messageDiv.style.paddingRight = '35px';
    messageDiv.style.border = '1px solid ' + borderColor;
    messageDiv.style.borderRadius = '5px';
    messageDiv.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
    messageDiv.style.zIndex = '1000';
    document.body.appendChild(messageDiv);

    closeBtn.onclick = function() {
        messageDiv.remove();
    };

    setTimeout(function() {
        if (document.body.contains(messageDiv)) {
            document.body.removeChild(messageDiv);
        }
    }, duration);
}