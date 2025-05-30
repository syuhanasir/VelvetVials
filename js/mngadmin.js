function updateAdmin() {
    const adminID = document.getElementById('adminID').value;
    const adminName = document.getElementById('adminName').value;
    const adminEmail = document.getElementById('adminEmail').value;
    const adminPassword = document.getElementById('adminPassword').value;
    const adminPhoneNum = document.getElementById('adminPhoneNum').value;

    const data = {
        adminID,
        adminName,
        adminEmail,
        adminPassword,
        adminPhoneNum,
    };

    fetch('crudadmin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert('Admin details updated successfully.');
                location.reload();
            } else {
                alert('Error updating admin details: ' + (data.error || 'Unknown error.'));
            }
        })
        .catch((error) => console.error('Error:', error));
}
