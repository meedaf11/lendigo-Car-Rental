// Load header and footer
fetch("header.html")
  .then((response) => response.text())
  .then((data) => {
    document.getElementById("header-placeholder").innerHTML = data;
    const script = document.createElement("script");
    script.src = "js/layout.js";
    document.body.appendChild(script);
  });

fetch("footer.html")
  .then((response) => response.text())
  .then((data) => {
    document.getElementById("footer-placeholder").innerHTML = data;
    const script = document.createElement("script");
    script.src = "js/layout.js";
    document.body.appendChild(script);
  });

// Form validation and value saving
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contact-form");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    // Get input values
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const subject = document.getElementById("subject").value.trim();
    const message = document.getElementById("message").value.trim();

    // Basic validation (client-side)
    if (!name || !email || !subject || !message) {
      alert("Please fill in all fields.");
      return;
    }

    // Prepare POST data
    const formData = new URLSearchParams();
    formData.append("name", name);
    formData.append("email", email);
    formData.append("subject", subject);
    formData.append("message", message);

    // Send data using fetch to send_contact.php
    fetch("api/send_contact.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: formData.toString(),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          alert(data.message); // Success message
          form.reset();
        } else {
          alert("Error: " + data.message); // Server-side validation or error
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
        alert("Something went wrong. Please try again later.");
      });
  });
});

