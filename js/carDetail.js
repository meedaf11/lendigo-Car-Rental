document.addEventListener("DOMContentLoaded", () => {
    // استخراج car_id من الرابط
    const urlParams = new URLSearchParams(window.location.search);
    const carId = urlParams.get('car_id');

    if (!carId) {
        console.error("car_id not found in URL");
        return;
    }

    fetch(`api/get_car_byId.php?car_id=${carId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }


            displayCarDetails(data);

            fetch(`api/get_car_reviews.php?car_id=${carId}`)
                .then(response => response.json())
                .then(reviews => {
                    displayCarReviews(reviews);
                })
                .catch(error => {
                    console.error("Error loading reviews:", error);
                });

        })
        .catch(error => {
            console.error("Fetch error:", error);
        });
});


function displayCarDetails(car) {

    console.log(car)

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


    let isAuto = car.isAutomatic === "1" ? "Automatic" : "Manual"

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

function displayCarReviews(reviews) {
    const wrapper = document.querySelector(".reviews-wrapper");
    wrapper.innerHTML = ""; // تفريغ القديم
  
    if (reviews.length === 0) {
      wrapper.innerHTML = "<p>No reviews yet.</p>";
      return;
    }
  
    reviews.forEach(review => {
      const card = document.createElement("div");
      card.classList.add("review-card");
  
      card.innerHTML = `
        <div class="review-header">
          <img src="assets/images/quote.svg" class="quote-icon"/>
          <p class="review-author">${review.user_name}</p>
          <div class="review-rating">
              <span class="rating-number">${parseFloat(review.rating).toFixed(1)}</span>
              <span class="star-icon">⭐</span>
          </div>
        </div>
        <p class="review-text">
          ${review.review_text}
        </p>
      `;
  
      wrapper.appendChild(card);
    });
  }
  
  
