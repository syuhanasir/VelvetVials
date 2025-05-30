
function showMembershipDropdown() {
    document.getElementById('membership-dropdown').style.display = 'block';
}

function showPaymentSection() {
    var membershipSelect = document.getElementById('membership-select');
    var selectedPlan = membershipSelect.value;

    if (selectedPlan) {
        document.getElementById('selectedPlan').textContent = selectedPlan;
        document.getElementById('payment-section').style.display = 'block';
        document.getElementById('membership-dropdown').style.display = 'none'; 

        document.getElementById('membership').value = selectedPlan;
    } else {
        alert("Please select a membership to upgrade.");
    }
}

function makePayment() {
    var selectedPlan = document.getElementById('selectedPlan').textContent;
    var amount;

    switch (selectedPlan) {
        case 'Silver':
            amount = "$20";
            break;
        case 'Gold':
            amount = "$30";
            break;
        case 'Platinum':
            amount = "$40";
            break;
        default:
            alert("Invalid membership selection.");
            return;
    }

    var transactionID = 'TX' + Math.floor(Math.random() * 1000000);

    document.getElementById('receiptPlan').textContent = selectedPlan;
    document.getElementById('receiptAmount').textContent = amount;
    document.getElementById('transactionID').textContent = transactionID;

    document.getElementById('payment-section').style.display = 'none';
    document.getElementById('receipt-section').style.display = 'block';

}

function confirmCancelMembership() {
    var confirmation = confirm("Are you sure you want to cancel your membership?");
    if (confirmation) {
        
        var cancelForm = document.createElement('form');
        cancelForm.method = 'POST';
        cancelForm.innerHTML = '<input type="hidden" name="cancel_membership" value="1" />';
        document.body.appendChild(cancelForm);
        cancelForm.submit();
    }
}

function updatePrice() {
    const membershipSelect = document.querySelector('select[name="membership"]');
    const selectedOption = membershipSelect.options[membershipSelect.selectedIndex];
    const priceDisplay = document.getElementById('price-display');

    if (selectedOption && priceDisplay) {
        priceDisplay.textContent = selectedOption.dataset.price
            ? `Price: RM${selectedOption.dataset.price}`
            : "";
    }
}
