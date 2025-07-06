document.addEventListener("DOMContentLoaded", function () {
    let notification = document.getElementById("notification");
    
    if (notification) {
        // Afficher la notification
        notification.classList.add("show");

        // La masquer après 3 secondes avec animation
        setTimeout(() => {
            notification.classList.remove("show");
        }, 3000);
    }
});
