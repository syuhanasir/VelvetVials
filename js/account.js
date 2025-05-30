
const profileSection = document.getElementById('profile-section');
const editProfileSection = document.getElementById('edit-profile-section');
const orderHistorySection = document.getElementById('order-history-section');
const editProfileBtn = document.getElementById('edit-profile-btn');
const orderHistoryBtn = document.getElementById('order-history-btn');


function showSection(section) {
    profileSection.style.display = 'none';
    editProfileSection.style.display = 'none';
    orderHistorySection.style.display = 'none';
    section.style.display = 'block';
}

showSection(profileSection);

editProfileBtn.addEventListener('click', () => {
    showSection(editProfileSection);
});

orderHistoryBtn.addEventListener('click', () => {
    showSection(orderHistorySection);
});

    document.getElementById('profile-btn').addEventListener('click', function() {
        document.getElementById('profile-section').style.display = 'block';
        document.getElementById('order-history-section').style.display = 'none';
        document.getElementById('edit-profile-section').style.display = 'none';
    });

    document.getElementById('order-history-btn').addEventListener('click', function() {
        document.getElementById('profile-section').style.display = 'none';
        document.getElementById('order-history-section').style.display = 'block';
        document.getElementById('edit-profile-section').style.display = 'none';
    });

    document.getElementById('edit-profile-btn').addEventListener('click', function() {
        document.getElementById('profile-section').style.display = 'none';
        document.getElementById('order-history-section').style.display = 'none';
        document.getElementById('edit-profile-section').style.display = 'block';
    });