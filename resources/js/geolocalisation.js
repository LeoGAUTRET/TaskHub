// geolocation.js
console.log('avant ');

export function requestGeolocation() {
    console.log('dedans');

    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            document.cookie = `latitude=${latitude}`;
            document.cookie = `longitude=${longitude}`;

            // Faites quelque chose avec les coordonnées (par exemple, envoyez-les au serveur)
            console.log("Latitude : " + latitude + " Longitude : " + longitude);
        });
    } else {
        console.log("La géolocalisation n'est pas disponible sur ce navigateur.");
    }
}
