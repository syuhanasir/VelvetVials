document.addEventListener("DOMContentLoaded", () => {
    fetch("getMetrics.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }

            document.getElementById("totalOrders").textContent = data.totalOrders || 0;
            document.getElementById("totalUsers").textContent = data.totalUsers || 0;
            document.getElementById("totalProducts").textContent = data.totalProducts || 0;
        })
        .catch(error => console.error("Error fetching metrics:", error));
});
