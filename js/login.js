const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");

document.getElementById("showRegister").addEventListener("click", (e) => {
  e.preventDefault();
  loginForm.style.display = "none";
  registerForm.style.display = "block";
});

document.getElementById("showLogin").addEventListener("click", (e) => {
  e.preventDefault();
  registerForm.style.display = "none";
  loginForm.style.display = "block";
});


document.getElementById("loginForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();

  if (email === "" || password === "") {
    alert("Please fill in all fields.");
    return;
  }

  if (!email.includes("@")) {
    alert("Please enter a valid email address.");
    return;
  }

  // Send POST request to login.php
  fetch("api/login.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({
      email: email,
      password: password
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        window.location.href = "index.html"; // Redirect to homepage
      } else {
        alert(data.message); // Show error message
      }
    })
    .catch((error) => {
      console.error("Login failed:", error);
      alert("Something went wrong. Please try again.");
    });
});


document.getElementById("registerForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const fullName = document.getElementById("fullName").value.trim();
  const username = document.getElementById("username").value.trim();
  const email = document.getElementById("registerEmail").value.trim();
  const password = document.getElementById("registerPassword").value.trim();
  const confirmPassword = document.getElementById("confirmPassword").value.trim();
  const phoneNumber = document.getElementById("phoneNumber").value.trim();

  if (!fullName || !username || !email || !password || !confirmPassword || !phoneNumber) {
    alert("Please fill in all fields.");
    return;
  }

  if (!email.includes("@")) {
    alert("Please enter a valid email.");
    return;
  }

  if (password !== confirmPassword) {
    alert("Passwords do not match.");
    return;
  }

  // Send data to register.php
  fetch("api/register.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: new URLSearchParams({
      fullName: fullName,
      username: username,
      email: email,
      password: password,
      confirmPassword: confirmPassword,
      phoneNumber: phoneNumber
    })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(data.message);
        // Optionally redirect to login
        document.getElementById("showLogin").click();
      } else {
        alert(data.message);
      }
    })
    .catch(error => {
      console.error("Registration failed:", error);
      alert("Something went wrong. Please try again.");
    });
});
