
function validateForm() {
    let fail = ""; 

    const email = document.getElementById('adminEmail'
    ).value;
    const password = document.getElementById('adminPassword').value;

    fail += validateEmail(email);
    fail += validatePassword(password);

    if (fail === "") {
        return true;
    } else {
        document.getElementById("error-msg").innerHTML = fail.replace(/\n/g, "<br>");
        return false;
    }
}

function validateEmail(field) {
    if (field === "") {
        return "<p>Email is required.</p>";
    } else if (!field.includes("@") ||!field.includes(".")) {
        return "<p>Please enter a valid email address.</p>";
    }
    return "";
}

function validatePassword(field) {
    if (field === "") {
        return "<p>Password is required.</p>";
    } else if (field.length < 6) {
        return "<p>Password must be at least 6 characters long.</p>";
    }
    return "";
}