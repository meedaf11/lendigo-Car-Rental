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

let allReviews = []; // هذا يخزن جميع التقييمات
let currentIndex = 0; // هذا رقم أول تقييم سيتم عرضه (مثلاً 0 تعني: 0،1،2)

function displayCarReviews(reviews) {
  allReviews = reviews; // نخزن كل التقييمات في متغير عام
  currentIndex = 0; // نبدأ من أول تقييم
  showCurrentReviews(); // نعرض أول 3 تقييمات
}

function showCurrentReviews() {
  const wrapper = document.querySelector(".reviews-wrapper");
  wrapper.innerHTML = ""; // نحذف كل شيء قديم

  if (allReviews.length === 0) {
    wrapper.innerHTML = "<p>No reviews yet.</p>";
    return;
  }

  // سنعرض 3 تقييمات: من currentIndex وحتى (currentIndex + 2)
  for (let i = 0; i < 3; i++) {
    // نحسب رقم التقييم، وإذا وصلنا للنهاية نرجع للبداية (cycle)
    const index = (currentIndex + i) % allReviews.length;
    const review = allReviews[index];

    const card = document.createElement("div");
    card.classList.add("review-card");

    // نضع محتوى التقييم في البطاقة
    card.innerHTML = `
      <div class="review-header">
        <img src="assets/images/quote.svg" class="quote-icon"/>
        <p class="review-author">${review.user_name}</p>
        <div class="review-rating">
            <span class="rating-number">${parseFloat(review.rating).toFixed(
              1
            )}</span>
            <span class="star-icon">⭐</span>
        </div>
      </div>
      <p class="review-text">
        ${review.review_text}
      </p>
    `;

    // نضيف البطاقة إلى الـ wrapper
    wrapper.appendChild(card);
  }

  // حركة انتقال بسيطة (أنميشن)
  wrapper.style.transition = "transform 0.4s ease";
  wrapper.style.transform = "translateX(0)";
}

document.getElementById("arrow_right").addEventListener("click", () => {
  // نحرك إلى الأمام بمقدار 1
  currentIndex = (currentIndex + 1) % allReviews.length;
  showCurrentReviews();
});

document.getElementById("arrow_left").addEventListener("click", () => {
  // نحرك للخلف وإذا رجعنا قبل البداية نذهب للنهاية
  currentIndex = (currentIndex - 1 + allReviews.length) % allReviews.length;
  showCurrentReviews();
});
