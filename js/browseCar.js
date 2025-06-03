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


// Load car data and populate filter options
fetch("api/get_cars.php")
  .then((res) => res.json())
  .then((cars) => {
    // Fill brand select
    const brands = [...new Set(cars.map((car) => car.brand))];
    const brandSelect = document.getElementById("brand");
    brands.forEach((brand) => {
      const opt = document.createElement("option");
      opt.value = brand;
      opt.textContent = brand;
      brandSelect.appendChild(opt);
    });

    // Fill release year select
    const years = [...new Set(cars.map((car) => car.model))].sort((a, b) => b - a);
    const releaseSelect = document.getElementById("release");
    years.forEach((year) => {
      const opt = document.createElement("option");
      opt.value = year;
      opt.textContent = year;
      releaseSelect.appendChild(opt);
    });

    // Fill rating select
    const ratings = [...new Set(cars.map((car) => Math.floor(car.car_rating)))].sort((a, b) => b - a);
    const ratingSelect = document.getElementById("rating");
    ratings.forEach((rating) => {
      const opt = document.createElement("option");
      opt.value = rating;
      opt.textContent = `${rating} Stars & up`;
      ratingSelect.appendChild(opt);
    });

    // Optional: Store cars in memory if needed for filtering later
    window.allCars = cars;
  })
  .catch((err) => console.error("Error fetching cars:", err));


  function displayAllCars(cars) {
    const container = document.getElementById("contentSide");
    container.innerHTML = "";
  
    cars.forEach(car => {
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
        <button class="cartBookBtn">View Car</button>
      `;
      container.appendChild(card);
    });
  }

  fetch("api/get_cars.php")
  .then(res => res.json())
  .then(cars => {
    window.allCars = cars; 
    displayAllCars(cars);  
  });



  document.querySelector(".filterBtn").addEventListener("click", () => {
  
    const priceMin = parseFloat(document.getElementById("priceMin").value) || 0;
    const priceMax = parseFloat(document.getElementById("priceMax").value) || Infinity;
    const brand = document.getElementById("brand").value;
    const rating = parseFloat(document.getElementById("rating").value) || 0;
    const release = document.getElementById("release").value;
    const onlyAvailable = document.getElementById("available").checked;
  
  
    const filteredCars = window.allCars.filter((car) => {
      const isWithinPrice = car.price_per_day >= priceMin && car.price_per_day <= priceMax;
      const isBrandMatch = !brand || car.brand === brand;
      const isRatingMatch = !rating || Math.floor(car.car_rating) >= rating;
      const isReleaseMatch = !release || car.model == release;
      const isAvailableMatch = !onlyAvailable || car.availability_status === "available";
  
      return isWithinPrice && isBrandMatch && isRatingMatch && isReleaseMatch && isAvailableMatch;
    });
  
    // 3. عرض النتائج
    displayAllCars(filteredCars);
  });
  