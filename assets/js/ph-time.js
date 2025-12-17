// ph-time.js
// Fetches and displays the current Philippine time in the dashboard

document.addEventListener('DOMContentLoaded', function() {
    var phTimeEl = document.getElementById('ph-time-api');
    if (!phTimeEl) return;

    var currentDate = null;
    var timer = null;

    function displayPhTime() {
        if (!currentDate) return;
        var options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        phTimeEl.innerHTML = '<strong>PH Time: ' + currentDate.toLocaleString('en-US', options) + '</strong>';
    }

    function tick() {
        if (currentDate) {
            currentDate.setSeconds(currentDate.getSeconds() + 1);
            displayPhTime();
        }
    }

    function updatePhTime() {
        phTimeEl.innerHTML = '<strong>PH Time: Loading...</strong>';
        fetch('https://api.timezonedb.com/v2.1/get-time-zone?key=GXVYQ374XGJX&format=json&by=zone&zone=Asia/Manila')
            .then(response => response.json())
            .then(data => {
                if (data && data.status === 'OK' && data.formatted) {
                    currentDate = new Date(data.formatted.replace(' ', 'T'));
                    displayPhTime();
                } else {
                    phTimeEl.innerHTML = '<strong>PH Time: unavailable</strong>';
                    currentDate = null;
                }
            })
            .catch(() => {
                phTimeEl.innerHTML = '<strong>PH Time: unavailable</strong>';
                currentDate = null;
            });
    }

    updatePhTime();
    timer = setInterval(tick, 1000); // Update every second
    setInterval(updatePhTime, 60000); // Sync with API every minute
});
