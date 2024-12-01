const notificationIcon = document.getElementById('notification-icon');
const notificationPopup = document.getElementById('notification-popup');

notificationIcon.addEventListener('click', function() {
    notificationPopup.classList.toggle('active');
});