document.addEventListener("DOMContentLoaded", function () {
    
    window.toggleStatus = function (userId, newStatus) {
        
        fetch("cruduser.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `action=toggleStatus&userId=${userId}&userStatus=${newStatus}`
        })
        .then(response => response.text())
        .then(responseText => {
            console.log(responseText); 
            location.reload();
        })
        .catch(error => console.error("Error:", error));
    };

    window.approveMembership = function (userId, membershipRequest, action) {
        fetch("cruduser.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `action=approveMembership&userId=${userId}&membershipRequest=${membershipRequest}&decision=${action}`
        })
        .then(response => response.text())
        .then(alert)
        .catch(console.error);
    };
});
