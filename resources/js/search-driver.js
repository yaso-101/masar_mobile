document.addEventListener('DOMContentLoaded', () => {
    // 1. Look for the hidden element containing the ride ID
    const rideDataElement = document.getElementById('ride-data');

    // 2. Only run the checking logic if we are actually on the search page!
    if (rideDataElement) {
        const rideId = rideDataElement.getAttribute('data-ride-id');

        const checkInterval = setInterval(() => {
            fetch(`/student/check-ride/${rideId}`)
                .then(response => response.json())
                .then(data => {
                    // If a driver accepted it, stop checking and redirect!
                    if (data.has_driver && data.status === 'accepted') {
                        clearInterval(checkInterval);
                        window.location.href = `/found-ride/${rideId}`;
                    }
                })
                .catch(error => console.error('Error checking ride status:', error));
        }, 3000); // Checks every 3 seconds
    }
});
