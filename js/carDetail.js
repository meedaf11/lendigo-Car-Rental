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

let currentAgencyId = null;

document.addEventListener("DOMContentLoaded", () => {
  // استخراج car_id من الرابط
  const urlParams = new URLSearchParams(window.location.search);
  const carId = urlParams.get("car_id");

  if (!carId) {
    console.error("car_id not found in URL");
    return;
  }

  fetch(`api/get_car_byId.php?car_id=${carId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        console.error("Error:", data.error);
        return;
      }

      displayCarDetails(data);

      fetch(`api/get_car_reviews.php?car_id=${carId}`)
        .then((response) => response.json())
        .then((reviews) => {
          displayCarReviews(reviews);
        })
        .catch((error) => {
          console.error("Error loading reviews:", error);
        });
    })
    .catch((error) => {
      console.error("Fetch error:", error);
    });
});

function displayCarDetails(car) {
  console.log(car);

  getAgencyInfo(car.agency_id);

  // تحديث صورة السيارة
  const carImage = document.querySelector(".car-image img");
  carImage.src = car.image_url;
  carImage.alt = car.car_name;

  const carTitle = document.querySelector(".car-title");
  carTitle.textContent = `${car.car_name} ${car.model}`;

  const carBrand = document.getElementById("carBrand");
  carBrand.textContent = `${car.brand}`;

  const carModel = document.getElementById("carModel");
  carModel.textContent = `${car.model}`;

  const carFuel = document.getElementById("carFuel");
  carFuel.textContent = `${car.car_fuel}`;

  const carType = document.getElementById("carType");
  carType.textContent = `${car.car_type}`;

  const carPlaces = document.getElementById("carPlaces");
  carPlaces.textContent = `${car.places}`;

  let isAuto = car.isAutomatic === "1" ? "Automatic" : "Manual";

  const Gear = document.getElementById("carGear");
  Gear.textContent = `${isAuto}`;

  const Kilometers = document.getElementById("carKilometers");
  Kilometers.textContent = `${car.kilometers}`;

  const price = document.getElementById("carPrice");
  price.textContent = `${car.price_per_day} DH`;

  // التقييم
  const ratingNumber = document.querySelector(".rating-number");
  ratingNumber.textContent = parseFloat(car.car_rating).toFixed(1);

  // الوصف
  const description = document.querySelector(".description-text");
  description.textContent = car.description;
}

let allReviews = [];
let currentIndex = 0;
let isTransitioning = false;
const reviewsPerPage = 3;

function displayCarReviews(reviews) {
  allReviews = reviews;
  currentIndex = 0;
  createPaginationDots();
  showCurrentReviews();
}

function createPaginationDots() {
  const dotsContainer = document.getElementById("paginationDots");
  dotsContainer.innerHTML = "";

  const totalPages = Math.ceil(allReviews.length / reviewsPerPage);

  for (let i = 0; i < totalPages; i++) {
    const dot = document.createElement("div");
    dot.classList.add("pagination-dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => goToPage(i));
    dotsContainer.appendChild(dot);
  }
}

function updatePaginationDots() {
  const dots = document.querySelectorAll(".pagination-dot");
  const currentPage = Math.floor(currentIndex / reviewsPerPage);

  dots.forEach((dot, index) => {
    dot.classList.toggle("active", index === currentPage);
  });
}

function showCurrentReviews(direction = "none") {
  if (isTransitioning) return;

  const wrapper = document.getElementById("reviewsWrapper");

  if (allReviews.length === 0) {
    wrapper.innerHTML =
      "<p style='text-align: center; color: #666; padding: 40px;'>No reviews available yet.</p>";
    return;
  }

  isTransitioning = true;
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

      const reviewsToShow = Math.min(reviewsPerPage, allReviews.length);
      const fragment = document.createDocumentFragment();

      for (let i = 0; i < reviewsToShow; i++) {
        const index = (currentIndex + i) % allReviews.length;
        const review = allReviews[index];

        const card = document.createElement("div");
        card.classList.add("review-card");

        // Add active class to middle card for emphasis
        if (i === Math.floor(reviewsToShow / 2)) {
          card.classList.add("active");
        }

        card.innerHTML = `
                        <div class="review-header">
                           <img src="assets/images/quote.svg" class="quote-icon" alt="">
                            <p class="review-author">${review.user_name}</p>
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
      updatePaginationDots();

      // Remove loading state and transition classes after animation
      setTimeout(() => {
        wrapper.classList.remove("loading");
        wrapper.querySelectorAll(".review-card").forEach((card) => {
          card.classList.remove("entering");
        });
        isTransitioning = false;
      }, 300);
    },
    direction === "none" ? 0 : 300
  );
}

function goToPage(pageIndex) {
  if (isTransitioning) return;
  currentIndex = pageIndex * reviewsPerPage;
  showCurrentReviews();
}

function nextReviews() {
  if (isTransitioning) return;
  currentIndex = (currentIndex + reviewsPerPage) % allReviews.length;
  showCurrentReviews("right");
}

function prevReviews() {
  if (isTransitioning) return;
  currentIndex =
    (currentIndex - reviewsPerPage + allReviews.length) % allReviews.length;
  showCurrentReviews("left");
}

// Event listeners
document.getElementById("arrow_right").addEventListener("click", nextReviews);
document.getElementById("arrow_left").addEventListener("click", prevReviews);

// Touch/swipe support for mobile
let startX = 0;
let endX = 0;

document
  .getElementById("reviewsWrapper")
  .addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
  });

document.getElementById("reviewsWrapper").addEventListener("touchend", (e) => {
  endX = e.changedTouches[0].clientX;
  const difference = startX - endX;

  if (Math.abs(difference) > 50) {
    // Minimum swipe distance
    if (difference > 0) {
      nextReviews(); // Swipe left - next
    } else {
      prevReviews(); // Swipe right - previous
    }
  }
});

// Keyboard navigation
document.addEventListener("keydown", (e) => {
  if (e.key === "ArrowRight") {
    nextReviews();
  } else if (e.key === "ArrowLeft") {
    prevReviews();
  }
});

// Auto-play functionality (optional)
let autoPlayInterval;

function startAutoPlay() {
  autoPlayInterval = setInterval(() => {
    if (!isTransitioning) {
      nextReviews();
    }
  }, 5000); // Change every 5 seconds
}

function stopAutoPlay() {
  clearInterval(autoPlayInterval);
}

// Auto-play controls
const container = document.querySelector(".carReviewsContainer");
container.addEventListener("mouseenter", stopAutoPlay);
container.addEventListener("mouseleave", startAutoPlay);

// Start auto-play
startAutoPlay();

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

document.querySelector(".book-btn").addEventListener("click", () => {
  checkLoginAndProceed(() => {
    alert("يمكنك الآن المتابعة إلى الحجز.");
  });
});

document.getElementById("placeReviewBtn").addEventListener("click", () => {
  checkLoginAndProceed(() => {
    alert("يمكنك الآن إضافة تقييم.");
  });
});

function getAgencyInfo(agencyId) {
  currentAgencyId = agencyId;

  fetch(`api/get_top_agency_cars.php?agency_id=${agencyId}`)
    .then((response) => {
      if (!response.ok) throw new Error("Fetch error: " + response.status);
      return response.json();
    })
    .then((data) => {
      if (!data.success) throw new Error(data.message || "Server error");

      const cars = data.cars.map((car) => {
        return {
          ...car,
          car_rating: parseFloat(car.car_rating),
          isAutomatic: parseInt(car.isAutomatic) === 1 ? "YES" : "NO",
          car_fuel: car.car_fuel || "Unknown",
        };
      });

      const container = document.querySelector(".agencyCarsCartContainer");
      container.innerHTML = ""; // تفريغ الحاوية

      cars.forEach((car) => {
        const card = document.createElement("div");
        card.className = "topCarCart"; // نفس الكلاس لجعل التصميم متناسق

        card.innerHTML = `
          <img class="carImg" src="${car.image_url}" alt="${car.car_name}">
          <h3 class="cardCarTitle">${car.car_name} ${car.model}</h3>
          <div class="cartTxtContainer">
            <span>Kilometers</span>
            <p><span class="carKm">${car.kilometers}</span> KM</p>
          </div>
          <div class="cartTxtContainer">
            <span>Automatic</span>
            <span class="carAuto">${car.isAutomatic}</span>
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
              <span>${car.car_rating.toFixed(2)}</span>
              <img src="assets/images/rating_ic.svg" alt="rating-icon" />
            </div>
          </div>
          <button class="cartBookBtn" data-id="${car.car_id}">See Car</button>
        `;

        card
          .querySelector(".cartBookBtn")
          .addEventListener("click", function () {
            const carId = this.getAttribute("data-id");
            window.location.href = `carDetail.html?car_id=${carId}`;
          });

        container.appendChild(card);
      });

      // تحديث اسم الوكالة في الهيدر
      document.querySelector(".carAgencyName").textContent =
        data.cars[0]?.agency_name || "Agency Name";
    })
    .catch((err) => {
      console.error(err);
      document.querySelector(".agencyCarsCartContainer").innerHTML = `
        <p style="color:red; text-align:center;">تعذّر تحميل سيارات الوكالة.</p>
      `;
    });

  document.getElementById("goToAgency").addEventListener("click", () => {
    if (currentAgencyId) {
      window.location.href = `agency.html?agency_id=${currentAgencyId}`;
    } else {
      console.error("لم يتم العثور على معرف الوكالة");
    }
  });
}
