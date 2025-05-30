function validateForm() {
    let errors = [];

    if (!validateField("firstName", /^[a-zA-Z\s]+$/, "First name cannot contain numbers or be empty.")) {
        errors.push("First name cannot contain numbers or be empty.");
    }
    if (!validateField("lastName", /^[a-zA-Z\s]+$/, "Last name cannot contain numbers or be empty.")) {
        errors.push("Last name cannot contain numbers or be empty.");
    }
    if (!validateField("email", /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, "Invalid email format.")) {
        errors.push("Invalid email format.");
    }
    if (!validateField("phone", /^[0-9]{10}$/, "Phone number must be exactly 10 digits.")) {
        errors.push("Phone number must be exactly 10 digits.");
    }
    if (!validateField("address", /.+/, "Address is required.")) {
        errors.push("Address is required.");
    }
    if (!validateField("password", /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/, "Password must be at least 8 characters long and include a lowercase letter, an uppercase letter, and a number.")) {
        errors.push("Password must be at least 8 characters long and include a lowercase letter, an uppercase letter, and a number.");
    }
    if (!validateDropdown("membership", "Please select a membership type.")) {
        errors.push("Please select a membership type.");
    }
    if (!validateCheckbox("terms", "You must agree to the Terms and Conditions.")) {
        errors.push("You must agree to the Terms and Conditions.");
    }

    if (errors.length > 0) {
        alert(errors.join("\n"));
        return false;
    }
    
    return true;
}

function validateField(id, regex, errorMessage) {
    let field = document.getElementById(id);
    if (!regex.test(field.value.trim())) {
        field.style.border = "2px solid red";
        return false;
    } else {
        field.style.border = "";
        return true;
    }
}

function validateDropdown(id, errorMessage) {
    let field = document.getElementById(id);
    if (field.value === "") {
        field.style.border = "2px solid red";
        return false;
    } else {
        field.style.border = "";
        return true;
    }
}

function validateCheckbox(id, errorMessage) {
    let field = document.getElementById(id);
    if (!field.checked) {
        return false;
    } else {
        return true;
    }
}
