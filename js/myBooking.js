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
  fetch("api/get_user_bookings.php")
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success" && data.bookings.length > 0) {
        renderBookings(data.bookings);
      } else {
        document.getElementById("noBookingsMessage").style.display = "block";
      }
    })
    .catch((err) => {
      console.error("Failed to load bookings:", err);
      document.getElementById("noBookingsMessage").style.display = "block";
    });
});

function renderBookings(bookings) {
  const container = document.getElementById("bookingList");

  bookings.forEach((booking) => {
    const days = calculateDays(booking.start_date, booking.end_date);
    const card = document.createElement("div");
    card.classList.add("booking-card");

    card.innerHTML = `
      <img src="${booking.image_url}" alt="${booking.car_name}" class="booking-image"/>
      <div class="booking-info">
        <h3>${booking.car_name} ${booking.model}</h3>
        <p><strong>Days:</strong> ${days}</p>
        <p><strong>Total:</strong> ${booking.total_price} DH</p>
        <p><strong>Status:</strong> ${booking.status}</p>
      </div>
    `;

    card.addEventListener("click", () => showBookingDetails(booking, days));
    container.appendChild(card);
  });
}

function calculateDays(start, end) {
  const startDate = new Date(start);
  const endDate = new Date(end);
  const diff = Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
  return diff;
}

function showBookingDetails(booking, days) {
  const overlay = document.createElement("div");
  overlay.classList.add("booking-overlay");

  overlay.innerHTML = `
    <div class="booking-popup">
      <button class="close-btn">&times;</button>
      <h2>${booking.car_name} ${booking.model}</h2>
      <img src="${booking.image_url}" alt="${booking.car_name}" class="popup-image"/>
      <p><strong>Booking ID:</strong> ${booking.booking_id}</p>
      <p><strong>Booking Date:</strong> ${booking.booking_date}</p>
      <p><strong>Status:</strong> ${booking.status}</p>
      <p><strong>Start Date:</strong> ${booking.start_date}</p>
      <p><strong>End Date:</strong> ${booking.end_date}</p>
      <p><strong>Total Days:</strong> ${days}</p>
      <p><strong>Price Per Day:</strong> ${booking.price_per_day} DH</p>
      <p><strong>Total Price:</strong> ${booking.total_price} DH</p>
      <button id="seeCarBtn" class="action-btn primary" style="margin-top: 16px;">🚗 See Car</button>
    </div>
  `;

  // إغلاق النافذة
  overlay.querySelector(".close-btn").addEventListener("click", () => {
    overlay.remove();
  });

  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) overlay.remove();
  });

  // زر "See Car"
  overlay.querySelector("#seeCarBtn").addEventListener("click", () => {
    window.location.href = `carDetail.html?car_id=${booking.car_id}`;
  });

  document.body.appendChild(overlay);
}
