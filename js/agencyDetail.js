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

fetch("api/changeCarsStatus.php")
  .then((response) => {
    if (!response.ok) {
      throw new Error("Failed to apply car status logic.");
    }
    return response.json();
  })
  .then((data) => {
    console.log("Car statuses updated:", data);
  })
  .catch((error) => {
    console.error("Error updating car statuses:", error);
  });

fetch("api/updateAgenciesRating.php")
  .then((response) => {
    if (!response.ok) {
      throw new Error("Failed to update agency ratings.");
    }
    return response.json();
  })
  .then((data) => {
    console.log("Agency ratings updated:", data);
  })
  .catch((error) => {
    console.error("Error updating agency ratings:", error);
  });

const urlParams = new URLSearchParams(window.location.search);
const agencyId = urlParams.get("agency_id");

if (!agencyId) {
  window.location.href = "agencies.html";
} else {
  fetch(`api/get_agency_info.php?agency_id=${agencyId}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.error) {
        console.error("Error:", data.error);
        return;
      }
      allContent(data);
    })
    .catch((error) => {
      console.error("Fetch error:", error);
    });
}

function allContent(agencyData) {
  console.log("Agency Info:", agencyData);

  const newImageUrl = agencyData.image;

  const agencyTitleName = document.getElementById("agencyTitleName");
  const agencyName = document.getElementById("agencyName");
  const agencyDescription = document.getElementById("agencyDescription");
  const heroImage = document.getElementById("heroBackImg");

  agencyTitleName.textContent = agencyData.name;
  agencyName.textContent = agencyData.name;
  agencyDescription.textContent = agencyData.description;
  heroImage.src = newImageUrl;

  // 📍 عرض الموقع على الخريطة
  const locationName = agencyData.location;
  const encodedLocation = encodeURIComponent(locationName);
  const mapUrl = `https://www.google.com/maps?q=${encodedLocation}&output=embed`;
  document.getElementById("mapFrame").src = mapUrl;

  // 🚗 جلب السيارات المرتبطة بالوكالة
  fetch(`api/getCarsByAgencyId.php?id=${agencyData.agency_id}`)
    .then((response) => {
      if (!response.ok) throw new Error("Failed to load cars");
      return response.json();
    })
    .then((carData) => {
      console.log("Cars data:", carData);
      displayAgencyCars(carData);
    })
    .catch((error) => {
      console.error("Error fetching cars:", error);
    });

  fetch(`api/get_agency_reviews.php?agency_id=${agencyData.agency_id}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        console.log("My Reviews: " + data.data);
        displayAgencyReviews(data.data);
      } else {
        console.error("Error loading agency reviews:", data.message);
        displayAgencyReviews([]);
      }
    })
    .catch((error) => {
      console.error("Error fetching agency reviews:", error);
      displayAgencyReviews([]);
    });
}

// Reviews Section :

let allAgencyReviews = [];
let currentAgencyIndex = 0;
let isAgencyTransitioning = false;
const agencyReviewsPerPage = 3;

// Function to display agency reviews
function displayAgencyReviews(reviews) {
  allAgencyReviews = reviews;
  currentAgencyIndex = 0;
  createAgencyPaginationDots();
  showCurrentAgencyReviews();
}

// Create pagination dots for agency reviews
function createAgencyPaginationDots() {
  const dotsContainer = document.querySelector(
    ".agencyReviews #paginationDots"
  );
  dotsContainer.innerHTML = "";

  const totalPages = Math.ceil(allAgencyReviews.length / agencyReviewsPerPage);

  for (let i = 0; i < totalPages; i++) {
    const dot = document.createElement("div");
    dot.classList.add("pagination-dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => goToAgencyPage(i));
    dotsContainer.appendChild(dot);
  }
}

// Update pagination dots for agency reviews
function updateAgencyPaginationDots() {
  const dots = document.querySelectorAll(".agencyReviews .pagination-dot");
  const currentPage = Math.floor(currentAgencyIndex / agencyReviewsPerPage);

  dots.forEach((dot, index) => {
    dot.classList.toggle("active", index === currentPage);
  });
}

// Show current agency reviews
function showCurrentAgencyReviews(direction = "none") {
  if (isAgencyTransitioning) return;

  const wrapper = document.querySelector(".agencyReviews #reviewsWrapper");

  if (allAgencyReviews.length === 0) {
    wrapper.innerHTML =
      "<p style='text-align: center; color: #666; padding: 40px;'>No agency reviews available yet.</p>";
    return;
  }

  isAgencyTransitioning = true;
  wrapper.classList.add("loading");

  // Add exit animation to current cards
  const currentCards = wrapper.querySelectorAll(".review-card");
  currentCards.forEach((card) => {
    card.classList.add("exiting");
  });

  // Wait for exit animation to complete
  setTimeout(
    () => {
      wrapper.innerHTML = "";

      const reviewsToShow = Math.min(
        agencyReviewsPerPage,
        allAgencyReviews.length
      );
      const fragment = document.createDocumentFragment();

      for (let i = 0; i < reviewsToShow; i++) {
        const index = (currentAgencyIndex + i) % allAgencyReviews.length;
        const review = allAgencyReviews[index];

        const card = document.createElement("div");
        card.classList.add("review-card");

        if (i === Math.floor(reviewsToShow / 2)) {
          card.classList.add("active");
        }

        card.innerHTML = `
          <div class="review-header">
             <img src="assets/images/quote.svg" class="quote-icon" alt="">
              <p class="review-author">${review.username}</p>
              <div class="review-rating">
                  <span class="rating-number">${parseFloat(
                    review.rating
                  ).toFixed(1)}</span>
                  <span class="star-icon">⭐</span>
              </div>
          </div>
          <p class="review-text">${review.review_text}</p>
        `;

        // Add entering animation
        card.classList.add("entering");
        fragment.appendChild(card);
      }

      wrapper.appendChild(fragment);
      updateAgencyPaginationDots();

      // Remove loading state and transition classes after animation
      setTimeout(() => {
        wrapper.classList.remove("loading");
        wrapper.querySelectorAll(".review-card").forEach((card) => {
          card.classList.remove("entering");
        });
        isAgencyTransitioning = false;
      }, 300);
    },
    direction === "none" ? 0 : 300
  );
}

// Navigate to specific page for agency reviews
function goToAgencyPage(pageIndex) {
  if (isAgencyTransitioning) return;
  currentAgencyIndex = pageIndex * agencyReviewsPerPage;
  showCurrentAgencyReviews();
}

// Next agency reviews
function nextAgencyReviews() {
  if (isAgencyTransitioning) return;
  currentAgencyIndex =
    (currentAgencyIndex + agencyReviewsPerPage) % allAgencyReviews.length;
  showCurrentAgencyReviews("right");
}

// Previous agency reviews
function prevAgencyReviews() {
  if (isAgencyTransitioning) return;
  currentAgencyIndex =
    (currentAgencyIndex - agencyReviewsPerPage + allAgencyReviews.length) %
    allAgencyReviews.length;
  showCurrentAgencyReviews("left");
}

// Event listeners for agency reviews
document.addEventListener("DOMContentLoaded", function () {
  // Arrow navigation for agency reviews
  const agencyLeftArrow = document.querySelector(".agencyReviews #arrow_left");
  const agencyRightArrow = document.querySelector(
    ".agencyReviews #arrow_right"
  );

  if (agencyLeftArrow) {
    agencyLeftArrow.addEventListener("click", prevAgencyReviews);
  }

  if (agencyRightArrow) {
    agencyRightArrow.addEventListener("click", nextAgencyReviews);
  }

  // Touch/swipe support for agency reviews
  const agencyWrapper = document.querySelector(
    ".agencyReviews #reviewsWrapper"
  );
  if (agencyWrapper) {
    let agencyStartX = 0;
    let agencyEndX = 0;

    agencyWrapper.addEventListener("touchstart", (e) => {
      agencyStartX = e.touches[0].clientX;
    });

    agencyWrapper.addEventListener("touchend", (e) => {
      agencyEndX = e.changedTouches[0].clientX;
      const difference = agencyStartX - agencyEndX;

      if (Math.abs(difference) > 50) {
        if (difference > 0) {
          nextAgencyReviews();
        } else {
          prevAgencyReviews();
        }
      }
    });
  }

  // Keyboard navigation for agency reviews
  document.addEventListener("keydown", (e) => {
    // Only handle if we're focused on agency reviews section
    const agencySection = document.querySelector(".agencyReviews");
    if (agencySection && e.key === "ArrowRight") {
      nextAgencyReviews();
    } else if (agencySection && e.key === "ArrowLeft") {
      prevAgencyReviews();
    }
  });

  // Auto-play functionality for agency reviews
  let agencyAutoPlayInterval;

  function startAgencyAutoPlay() {
    agencyAutoPlayInterval = setInterval(() => {
      if (!isAgencyTransitioning) {
        nextAgencyReviews();
      }
    }, 5000);
  }

  function stopAgencyAutoPlay() {
    clearInterval(agencyAutoPlayInterval);
  }

  // Auto-play controls for agency reviews
  const agencyContainer = document.querySelector(".agencyReviewsContainer");
  if (agencyContainer) {
    agencyContainer.addEventListener("mouseenter", stopAgencyAutoPlay);
    agencyContainer.addEventListener("mouseleave", startAgencyAutoPlay);

    // Start auto-play
    startAgencyAutoPlay();
  }

  // Agency review button functionality
  const agencyPlaceReviewBtn = document.querySelector(
    ".agencyReviews #placeReviewBtn"
  );
  if (agencyPlaceReviewBtn) {
    agencyPlaceReviewBtn.addEventListener("click", () => {
      checkLoginAndProceed(() => {});
    });
  }
});

// POPUP
function checkLoginAndProceed(callback) {
  fetch("api/check_login.php")
    .then((res) => res.json())
    .then((data) => {
      if (data.loggedIn) {
        callback();
      } else {
        showLoginPopup();
      }
    })
    .catch((err) => {
      console.error("Login check error:", err);
    });
}

function showLoginPopup() {
  const overlay = document.getElementById("loginPopupOverlay");
  overlay.style.display = "flex";
}

function hideLoginPopup() {
  const overlay = document.getElementById("loginPopupOverlay");
  overlay.style.display = "none";
}

document.getElementById("loginRedirectBtn").addEventListener("click", () => {
  window.location.href = "login.html";
});

document
  .getElementById("cancelPopupBtn")
  .addEventListener("click", hideLoginPopup);

document.getElementById("loginPopupOverlay").addEventListener("click", (e) => {
  if (e.target.id === "loginPopupOverlay") {
    hideLoginPopup();
  }
});

// Show review popup
function showReviewPopup() {
  document.getElementById("reviewPopupOverlay").style.display = "flex";
}

// Hide review popup
function hideReviewPopup() {
  document.getElementById("reviewPopupOverlay").style.display = "none";
}

document.getElementById("placeReviewBtn").addEventListener("click", () => {
  checkLoginAndProceed(() => {
    showReviewPopup();
  });
});

// Event: Cancel button
document
  .getElementById("cancelReviewPopupBtn")
  .addEventListener("click", hideReviewPopup);

document
  .getElementById("reviewPopupOverlay")
  .addEventListener("click", function (e) {
    const popupBox = document.getElementById("reviewPopup");
    if (!popupBox.contains(e.target)) {
      hideReviewPopup();
    }
  });

// Event: Submit review
document
  .getElementById("submitReviewForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const rating = document.getElementById("reviewRating").value;
    const text = document.getElementById("reviewText").value;

    if (!rating || !text) {
      alert("Please provide both rating and review.");
      return;
    }

    const payload = {
      agency_id: agencyId,
      rating: rating,
      review_text: text,
    };

    fetch("api/submit_agency_review.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          alert("Review submitted successfully!");
          hideReviewPopup();
          // Optionally refresh reviews or add it to the list dynamically
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch((err) => {
        console.error("Review submission error:", err);
        alert("Something went wrong while submitting the review.");
      });
  });

function displayAgencyCars(cars) {
  const container = document.getElementById("agencyCarsContainer");
  container.innerHTML = "";

  cars.forEach((car) => {
    const card = document.createElement("div");
    card.className = "CarCart";
    card.innerHTML = `
        <img class="carImg" src="${car.image_url}" alt="${car.car_name}">
        <h3 class="cardCarTitle">${car.car_name} ${car.model}</h3>
        <div class="cartTxtContainer">
          <span>Kilometers</span>
          <p><span class="carKm">${car.kilometers}</span> KM</p>
        </div>
        <div class="cartTxtContainer">
          <span>Automatic</span>
          <span class="carAuto">${car.isAutomatic ? "Yes" : "No"}</span>
        </div>
        <div class="cartTxtContainer">
          <span>Fuel Type</span>
          <span class="carFuel">${car.car_fuel}</span>
        </div>
        <div class="cartTxtContainer">
          <div class="cartPrice">
            <p>
              <span class="carPrice">${car.price_per_day}</span> dh
              <span class="pd">/Per Day</span>
            </p>
          </div>
          <div class="cartRating">
            <span>${parseFloat(car.car_rating).toFixed(2)}</span>
            <img src="assets/images/rating_ic.svg" alt="rating-icon" />
          </div>
        </div>
        <button class="cartBookBtn" data-id="${car.car_id}">View Car</button>
      `;
    container.appendChild(card);
    card.querySelector(".cartBookBtn").addEventListener("click", function () {
      const carId = this.getAttribute("data-id");
      window.location.href = `carDetail.html?car_id=${carId}`;
    });
  });
}
