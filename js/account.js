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


document.addEventListener('DOMContentLoaded', function () {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    fetch("api/get_user_profile.php")
        .then((res) => res.json())
        .then((response) => {
            if (response.status === "success") {
                const { full_name, email, phone_number, username } = response.data;

                // Set values in form
                document.getElementById("name").value = full_name;
                document.getElementById("email").value = email;
                document.getElementById("phone").value = phone_number;

                // Update welcome message
                document.getElementById("user-name").textContent = username;
            } else {
                console.error(response.message);
            }
        })
        .catch((error) => {
            console.error("Error loading profile data:", error);
        });

    tabLinks.forEach(link => {
        link.addEventListener('click', function () {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all tabs and contents
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Profile form submission
    document.getElementById('profile-form').addEventListener('submit', function (e) {
        e.preventDefault();

        // Get form data
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        // Update user name in header
        const userName = data.name || 'User';
        document.getElementById('user-name').textContent = userName;

        fetch("api/update_user_profile.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
            .then(res => res.json())
            .then(response => {
                if (response.status === "success") {
                    alert("Profile updated successfully.");
                } else {
                    alert("Error: " + response.message);
                }
            })
            .catch(error => {
                console.error("Update failed:", error);
                alert("Something went wrong while updating profile.");
            });

        // Show success message
        const successMessage = document.getElementById('success-message');
        successMessage.classList.add('show');

        // Hide success message after 3 seconds
        setTimeout(() => {
            successMessage.classList.remove('show');
        }, 3000);

        // In a real application, you would send the data to your server
        console.log('Profile updated:', data);
    });

    // Password form submission
    document.getElementById('password-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const current_password = document.getElementById("current-password").value.trim();
        const new_password = document.getElementById("new-password").value.trim();
        const confirm_password = document.getElementById("confirm-password").value.trim();

        // Basic frontend check (optional)
        if (!current_password || !new_password || !confirm_password) {
            alert("Please fill in all password fields.");
            return;
        }

        if (new_password !== confirm_password) {
            alert("New passwords do not match.");
            return;
        }

        // Send data to backend
        fetch("api/change_password.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                current_password,
                new_password,
                confirm_password
            })
        })
            .then(res => res.json())
            .then(response => {
                if (response.status === "success") {
                    alert(response.message);
                    document.getElementById('password-form').reset();
                } else {
                    alert("Error: " + response.message);
                }
            })
            .catch(error => {
                console.error("Password update failed:", error);
                alert("Something went wrong. Try again.");
            });
    });

    // Fetch and display messages
    fetch("api/get_user_messages.php")
        .then(res => res.json())
        .then(response => {
            if (response.status === "success") {
                const messages = response.data;
                const container = document.getElementById("messages-list");

                if (messages.length === 0) {
                    container.innerHTML = `<div class="empty-state"><h3>No messages yet</h3><p>You haven’t submitted any contact requests.</p></div>`;
                    return;
                }

                container.innerHTML = "";

                messages.forEach(msg => {
                    const item = document.createElement("div");
                    item.className = `message-item ${msg.status === "pending" ? "status-pending" : "status-answered"}`;

                    item.innerHTML = `
          <h3>${msg.subject}</h3>
          <p class="message-date">${new Date(msg.submitted_at).toLocaleString()}</p>
          <p><strong>Status:</strong> ${msg.status}</p>
        `;

                    if (msg.status === "answered") {
                        item.classList.add("clickable");
                        item.style.cursor = "pointer";
                        item.addEventListener("click", () => {
                            showAnswerPopup(msg.subject, msg.message, msg.answer);
                        });
                    }

                    container.appendChild(item);
                });
            }
        })
        .catch(error => {
            console.error("Error fetching messages:", error);
        });

    // Popup function
    function showAnswerPopup(subject, message, answer) {
        const popup = document.createElement("div");
        popup.className = "popup-overlay";
        popup.innerHTML = `
    <div class="popup-content">
      <h3>${subject}</h3>
      <p><strong>Your Message:</strong></p>
      <p>${message}</p>
      <hr/>
      <p><strong>Answer:</strong></p>
      <p>${answer}</p>
      <button class="close-popup">Close</button>
    </div>
  `;
        document.body.appendChild(popup);

        popup.querySelector(".close-popup").addEventListener("click", () => {
            popup.remove();
        });
    }


    // Inner tab switching in reviews
    document.querySelectorAll('.inner-tab-link').forEach(btn => {
        btn.addEventListener('click', function () {
            const target = this.getAttribute('data-inner-tab');

            // Toggle active class on buttons
            document.querySelectorAll('.inner-tab-link').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Toggle content
            document.querySelectorAll('.inner-tab-content').forEach(tab => tab.classList.remove('active'));
            document.getElementById(target).classList.add('active');
        });
    });

    // Fetch and display user reviews
    fetch("api/getReviewsById.php")
        .then(res => res.json())
        .then(response => {
            if (response.status === "success") {
                const { car_reviews, agency_reviews } = response.data;

                const carContainer = document.getElementById("car-reviews-list");
                const agencyContainer = document.getElementById("agency-reviews-list");

                if (car_reviews.length === 0) {
                    carContainer.innerHTML = `<div class="empty-state"><h3>No car reviews</h3><p>You haven't submitted any car reviews yet.</p></div>`;
                } else {
                    car_reviews.forEach(review => {
                        const item = document.createElement("div");
                        item.className = "review-item clickable";
                        item.style.cursor = "pointer";

                        item.innerHTML = `
            <h3>Rating: ${review.rating}</h3>
            <p>${review.review_text}</p>
            <p class="review-date">Date: ${review.created_at}</p>
        `;

                        // On click redirect to car detail page
                        item.addEventListener("click", () => {
                            window.location.href = `carDetail.html?car_id=${review.car_id}`;
                        });

                        carContainer.appendChild(item);
                    });
                }

                // Render Agency Reviews
                if (agency_reviews.length === 0) {
                    agencyContainer.innerHTML = `<div class="empty-state"><h3>No agency reviews</h3><p>You haven't submitted any agency reviews yet.</p></div>`;
                } else {
                    agency_reviews.forEach(review => {
                        const item = document.createElement("div");
                        item.className = "review-item clickable";
                        item.style.cursor = "pointer";

                        item.innerHTML = `
            <h3>Rating: ${review.rating}</h3>
            <p>${review.review_text}</p>
            <p class="review-date">Date: ${review.created_at}</p>
        `;

                        // On click redirect to agency detail page
                        item.addEventListener("click", () => {
                            window.location.href = `agency.html?agency_id=${review.agency_id}`;
                        });

                        agencyContainer.appendChild(item);
                    });
                }

            } else {
                console.error("Failed to load reviews:", response.message);
            }
        })
        .catch(error => {
            console.error("Error fetching reviews:", error);
        });



    // Add hover effects to form inputs
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function () {
            this.parentElement.style.transform = 'translateY(-2px)';
        });

        input.addEventListener('blur', function () {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });

    // Simulate loading animation for tabs
    tabLinks.forEach(link => {
        link.addEventListener('click', function () {
            const targetContent = document.getElementById(this.getAttribute('data-tab'));
            targetContent.style.opacity = '0';
            targetContent.style.transform = 'translateY(20px)';

            setTimeout(() => {
                targetContent.style.opacity = '1';
                targetContent.style.transform = 'translateY(0)';
            }, 50);
        });
    });
});

