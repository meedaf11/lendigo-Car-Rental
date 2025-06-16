// تحميل الهيدر والفوتر
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


document.addEventListener("DOMContentLoaded", () => {
  const findCarBtn = document.getElementById("findCarBtn");

  if (findCarBtn) {
    findCarBtn.addEventListener("click", () => {
      window.location.href = "browseCar.html";
    });
  }
});


document.addEventListener("DOMContentLoaded", async () => {
  const brandSelect = document.getElementById("brand");
  const modelSelect = document.getElementById("model");
  const citySelect = document.getElementById("city");

  let carData = [];

  // Fetch data
  try {
    const response = await fetch("api/get_cars.php");
    const text = await response.text();
    console.log("Raw response:", text);
    carData = JSON.parse(text);
    populateBrandOptions();
    updateModelOptions();
    updateCityOptions();
  } catch (error) {
    console.error("Failed to fetch or parse car data:", error);
  }

  // Populate brand options
  function populateBrandOptions() {
    const brands = [...new Set(carData.map((car) => car.brand))].sort();
    brandSelect.innerHTML = `<option value="">All Brands</option>`;
    brands.forEach((brand) => {
      const option = document.createElement("option");
      option.value = brand;
      option.textContent = brand;
      brandSelect.appendChild(option);
    });
  }

  // Update model options based on selected brand
  function updateModelOptions() {
    const selectedBrand = brandSelect.value;
    let filtered = carData;

    if (selectedBrand) {
      filtered = filtered.filter((car) => car.brand === selectedBrand);
    }

    const models = [...new Set(filtered.map((car) => car.model))].sort();
    modelSelect.innerHTML = `<option value="">All Models</option>`;
    models.forEach((model) => {
      const option = document.createElement("option");
      option.value = model;
      option.textContent = model;
      modelSelect.appendChild(option);
    });
  }

  // Update city options based on selected brand and model
  function updateCityOptions() {
    const selectedBrand = brandSelect.value;
    const selectedModel = modelSelect.value;

    let filtered = carData;

    if (selectedBrand) {
      filtered = filtered.filter((car) => car.brand === selectedBrand);
    }

    if (selectedModel) {
      filtered = filtered.filter((car) => car.model === selectedModel);
    }

    const cities = [
      ...new Set(filtered.map((car) => car.agency_city || "Unknown")),
    ].sort();
    citySelect.innerHTML = `<option value="">All Cities</option>`;
    cities.forEach((city) => {
      const option = document.createElement("option");
      option.value = city;
      option.textContent = city;
      citySelect.appendChild(option);
    });
  }

  // Event listeners
  brandSelect.addEventListener("change", () => {
    updateModelOptions();
    updateCityOptions();
  });

  modelSelect.addEventListener("change", () => {
    updateCityOptions();
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const dateDisplay = document.getElementById("date-display");


  let picker = null;

  if (dateDisplay) {
    picker = new Litepicker({
      element: dateDisplay,
      singleMode: false,
      format: "MMM D",
      minDate: new Date(),
      numberOfMonths: 2,
      numberOfColumns: 2,
      setup: (pickerInstance) => {
        pickerInstance.on("selected", (start, end) => {
          if (start && end) {
            const startFormatted = start.format("MMM D");
            const endFormatted = end.format("MMM D");
            dateDisplay.textContent = `${startFormatted} - ${endFormatted}`;
          }
        });
      },
    });

    dateDisplay.textContent = "Choose Date";
  }

  document.querySelector(".searchBtn").addEventListener("click", () => {
    const brand = document.getElementById("brand").value;
    const model = document.getElementById("model").value;
    const city = document.getElementById("city").value;
    const startDate = picker?.getStartDate()?.format("YYYY-MM-DD") || "";
    const endDate = picker?.getEndDate()?.format("YYYY-MM-DD") || "";

    const params = new URLSearchParams({
      brand,
      model,
      city,
      startDate,
      endDate
    });

    window.location.href = `browseCar.html?${params.toString()}`;
  });
});

// Get All Cars :

document.addEventListener("DOMContentLoaded", () => {
  fetch("api/get_cars.php")
    .then((response) => {
      if (!response.ok) throw new Error(`Fetch error: ${response.status}`);
      return response.json();
    })
    .then((cars) => {
      cars.forEach((car) => {
        car.car_rating = parseFloat(car.car_rating);
        car.isAutomatic = parseInt(car.isAutomatic) === 1 ? "YES" : "NO";
        if (!car.car_fuel) car.car_fuel = "Unknown";
      });

      const top4 = cars.sort((a, b) => b.car_rating - a.car_rating).slice(0, 4);

      const container = document.querySelector(".ratedCartContainer");
      container.innerHTML = "";

      top4.forEach((car) => {
        const card = document.createElement("div");
        card.className = "topCarCart";
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
          <button class="cartBookBtn" data-id="${car.car_id}">BOOK NOW</button>
        `;
        container.appendChild(card);
        card.querySelector(".cartBookBtn").addEventListener("click", function () {
          const carId = this.getAttribute("data-id");
          window.location.href = `carDetail.html?car_id=${carId}`;
        });
      });
    })
    .catch((err) => {
      console.error(err);
      document.querySelector(".ratedCartContainer").innerHTML = `
        <p style="color:red; text-align:center;">تعذّر تحميل السيارات.</p>
      `;
    });
});

// Call the PHP script to update reviews.json every time the page loads
fetch("api/get_reviews.php")
  .then((response) => {
    if (!response.ok) {
      throw new Error("Failed to update reviews.json");
    }
    return response.json();
  })
  .then((data) => {
    console.log("reviews.json updated", data);
  })
  .catch((error) => {
    console.error("Error updating reviews:", error);
  });

function showTab(tabId) {
  document
    .querySelectorAll(".content")
    .forEach((c) => c.classList.remove("active"));
  document.getElementById(tabId).classList.add("active");

  document
    .querySelectorAll(".tab")
    .forEach((t) => t.classList.remove("active_tab"));
  if (tabId === "car") {
    document.querySelectorAll(".tab")[0].classList.add("active_tab");
  } else {
    document.querySelectorAll(".tab")[1].classList.add("active_tab");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  let data;

  fetch("api/reviews.json")
    .then((response) => response.json())
    .then((json) => {
      data = json;
      loadContent("carReviews", "car");
      loadContent("agencyReviews", "agency");
    });

  function loadContent(type, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";
    data[type].slice(0, 4).forEach((item) => {
      const card = document.createElement("div");
      card.className = "card";
      card.innerHTML = `
          <div class="reviewInfo">
          <img src="assets/images/quote.svg" alt="" />
          <h3>${item.username} </h3>
          <span class="rating">${item.rating} ⭐</span>
          </div>
          
          <p>${item.text}</p>
          
          <button class="btn">${item.button}</button>
        `;

      const button = card.querySelector(".btn");
      button.addEventListener("click", () => {
        if (type === "carReviews" && item.car_id) {
          window.location.href = `carDetail.html?car_id=${item.car_id}`;
        } else if (type === "agencyReviews" && item.agency_id) {
          window.location.href = `agency.html?agency_id=${item.agency_id}`;
        }
      });

      container.appendChild(card);
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
  // Variable to hold FAQ data after fetching
  let faqData = [];

  // Fetch FAQ data from JSON file
  fetch("faq.json")
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Failed to fetch file: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      faqData = data;
      renderFAQ(); // Render FAQs after successful fetch
    })
    .catch((error) => {
      console.error("Error loading FAQs:", error);
      const container = document.getElementById("faq-container");
      container.innerHTML = `<p>Unable to load FAQs. Please try again later.</p>`;
    });

  // Render FAQ items into the page
  function renderFAQ() {
    const container = document.getElementById("faq-container");
    container.innerHTML = ""; // Clear any existing content

    faqData.forEach((item, index) => {
      // Create the main FAQ item container
      const faqItem = document.createElement("div");
      faqItem.classList.add("item");
      faqItem.setAttribute("data-index", index);

      // Create question element
      const questionEl = document.createElement("div");
      questionEl.classList.add("question");
      questionEl.textContent = item.question;

      // Create answer element
      const answerEl = document.createElement("div");
      answerEl.classList.add("answer");
      answerEl.textContent = item.answer;

      // Toggle “active” class on click to show/hide answer
      questionEl.addEventListener("click", () => {
        faqItem.classList.toggle("active");
      });

      // Append question and answer to the item container
      faqItem.appendChild(questionEl);
      faqItem.appendChild(answerEl);

      // Append the item container to the FAQ wrapper
      container.appendChild(faqItem);
    });
  }
});
