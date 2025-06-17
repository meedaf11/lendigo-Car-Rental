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

let allAgencies = [];
let filteredAgencies = [];

const searchInput = document.getElementById("agencySearchInput");
const searchButton = document.getElementById("searchBtn");
const agencyContainer = document.getElementById("agenciesCardsContainer");

fetch("api/get_agencies.php")
  .then((response) => {
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    return response.json();
  })
  .then((data) => {
    console.log("Fetched agencies:", data);

    allAgencies = data;
    filteredAgencies = allAgencies;
    renderAgencies(filteredAgencies);
  })
  .catch((error) => {
    console.error("Error fetching agencies:", error);
  });

searchButton.addEventListener("click", () => {
  const keyword = searchInput.value.toLowerCase().trim();
  filteredAgencies = allAgencies.filter((agency) =>
    agency.name.toLowerCase().includes(keyword)
  );
  renderAgencies(filteredAgencies);
});

function renderAgencies(agencies) {
  agencyContainer.innerHTML = "";

  if (agencies.length === 0) {
    agencyContainer.innerHTML = "<p>No agencies found.</p>";
    return;
  }

  agencies.forEach((agency) => {
    const card = document.createElement("div");
    card.className = "agency-card";

    card.innerHTML = `
    <img src="${agency.image}" alt="${agency.name}">
    <div class="content">
      <h3>${agency.name}</h3>
      <p><strong>City:</strong> ${agency.agency_city}</p>
      <p><strong>Rating:</strong> ${parseFloat(agency.rating).toFixed(2)} ⭐</p>
      <button class="see-agency-btn" onclick="window.location.href='agency.html?agency_id=${
        agency.agency_id
      }'">
        See Agency
      </button>
    </div>
  `;

    agencyContainer.appendChild(card);
  });
}


document.getElementById("addYourAgency").addEventListener("click", () => {
  fetch("api/check_login.php")
    .then(response => response.json())
    .then(data => {
      if (data.loggedIn) {
        // User is logged in, redirect to add agency page
        window.location.href = "addAgency.html";
      } else {
        // Not logged in, show the login popup
        document.getElementById("loginPopupOverlay").classList.add("show");
      }
    })
    .catch(error => {
      console.error("Login check failed:", error);
    });
});

// Handle login button inside popup
document.getElementById("loginRedirectBtn").addEventListener("click", () => {
  window.location.href = "login.html";
});

// Handle cancel button inside popup
document.getElementById("cancelPopupBtn").addEventListener("click", () => {
  document.getElementById("loginPopupOverlay").classList.remove("show");
});

// Close popup when clicking outside the login box
document.getElementById("loginPopupOverlay").addEventListener("click", (e) => {
  const popup = document.getElementById("loginPopup");
  if (!popup.contains(e.target)) {
    document.getElementById("loginPopupOverlay").classList.remove("show");
  }
});

