document.addEventListener("DOMContentLoaded", () => {

    const alerts = document.querySelectorAll(".alert");

    if (alerts.length) {
        //for of loop awaits each iteration
        const animateAlerts = async () => {
            // duration of each alert = animateAlert + removeAlert.
            for (const alert of alerts) {
                await animateAlert(alert);
                await removeAlert(alert);
            }
        };

        function animateAlert(alert) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    alert.classList.add("remove"); // class to animate the alert
                    resolve();
                }, 4000);
            });
        }

        function removeAlert(alert) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    alert.remove();
                    resolve();
                }, 2000);//css animation duration 
            });
        }

        animateAlerts();
    }
});

