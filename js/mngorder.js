document.addEventListener("DOMContentLoaded", function () {
    window.updateStatus = function (orderID, newStatus) {
        fetch("crudorder.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `orderID=${orderID}&newStatus=${newStatus}`
        })
        .then(response => response.text())
        .then(data => {
            if (data === "Success") {
                const row = document.getElementById(`row-${orderID}`);
                row.cells[5].textContent = newStatus;
            } else {
                alert("Error updating status: " + data);
            }
        })
        .catch(error => console.error('Error:', error));
    };
});
