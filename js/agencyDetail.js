fetch("header.html")
    .then((response) => response.text())
    .then((data) => {
        document.getElementById("header-placeholder").innerHTML = data;

        const script = document.createElement("script");
        script.src = "js/layout.js";
        document.body.appendChild(script);
    })
    .catch((error) => {
        console.error("There was a problem loading the header:", error);
    });

fetch("footer.html")
    .then((response) => response.text())
    .then((data) => {
        document.getElementById("footer-placeholder").innerHTML = data;

        const script = document.createElement("script");
        script.src = "js/layout.js";
        document.body.appendChild(script);
    })
    .catch((error) => {
        console.error("There was a problem loading the footer:", error);
    });


const urlParams = new URLSearchParams(window.location.search);
const agencyId = urlParams.get("agency_id");


if (!agencyId) {
    window.location.href = "agencies.html";
} else {
    fetch(`api/get_agency_info.php?agency_id=${agencyId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }
            allContent(data);
        })
        .catch(error => {
            console.error("Fetch error:", error);
        });
}


function allContent(agencyData){

    console.log(agencyData);
    console.log("الموقع:", agencyData.location);

    const locationName = agencyData.location;
    const encodedLocation = encodeURIComponent(locationName);
    const mapUrl = `https://www.google.com/maps?q=${encodedLocation}&output=embed`;
    document.getElementById("mapFrame").src = mapUrl;


}
