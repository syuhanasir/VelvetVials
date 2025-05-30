document.addEventListener("DOMContentLoaded", function () {
    const popup = document.getElementById("addProductPopup");
    const addProductForm = document.getElementById("addProductForm");

    function openPopup() {
        popup.style.display = "flex";
    }

    function closePopup() {
        popup.style.display = "none";
    }

    window.openPopup = openPopup;
    window.closePopup = closePopup;

    addProductForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const prodID = document.getElementById("prodID").value;
        const prodName = document.getElementById("prodName").value;
        const prodCat = document.getElementById("prodCat").value;
        const prodDesc = document.getElementById("prodDesc").value;
        const prodPrice = document.getElementById("prodPrice").value;
        const prodStock = document.getElementById("prodStock").value;

        fetch("crudprod.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `action=add&prodID=${prodID}&prodName=${prodName}&prodCat=${prodCat}&prodDesc=${prodDesc}&prodPrice=${prodPrice}&prodStock=${prodStock}`
        })
            .then(response => response.text())
            .then(responseText => {
                alert(responseText);
                console.log(responseText);
                location.reload();
            })
            .catch(error => console.error("Error:", error));
    });

    window.editProduct = function (prodID) {
        const newName = prompt("Enter new product name:");
        const newPrice = prompt("Enter new price:");
        const newStock = prompt("Enter new stock:");

        if (newName && newPrice && newStock) {
            fetch("crudprod.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=edit&prodID=${prodID}&prodName=${newName}&prodPrice=${newPrice}&prodStock=${newStock}`
            })
                .then(response => response.text())
                .then(responseText => {
                    alert(responseText);
                    console.log(responseText);
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        }
    };

    window.deleteProduct = function (prodID) {
        if (confirm("Are you sure?")) {
            fetch("crudprod.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=delete&prodID=${prodID}`
            })
                .then(response => response.text())
                .then(responseText => {
                    alert(responseText);
                    console.log(responseText);
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        }
    };
});
