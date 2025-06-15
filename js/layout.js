const hamburgerBtn = document.getElementById("hamburger-btn");
const mainNav = document.querySelector(".mainNav");

function toggleDropdown() {
  const dropdown = document.querySelector(".user-profile .dropdown");
  dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
}


const links = document.querySelectorAll(".mainNav ul li a");
const currentPage = window.location.pathname.split("/").pop(); 

links.forEach((link) => {
  const linkPage = link.getAttribute("href").split("/").pop(); 
  if (linkPage === currentPage) {
    link.classList.add("active");
  }
});


window.addEventListener("click", function (e) {
  const profile = document.querySelector(".user-profile");
  const dropdown = document.querySelector(".user-profile .dropdown");
  if (!profile.contains(e.target)) {
    dropdown.style.display = "none";
  }
});

hamburgerBtn.addEventListener("click", () => {
  mainNav.classList.toggle("open");
});

document.querySelectorAll(".mainNav ul li a").forEach((link) => {
  link.addEventListener("click", () => {
    mainNav.classList.remove("open");
  });
});



  const footerLinks = document.querySelectorAll("footer .top .column ul li a");

  footerLinks.forEach((link) => {
    const linkPage = link.getAttribute("href").split("/").pop();
    if (linkPage === currentPage) {
      link.classList.add("active");
    }
  });

  // Fetch login status and update dropdown menu
fetch("api/check_login.php")
  .then((res) => res.json())
  .then((data) => {
    const dropdown = document.querySelector(".user-profile .dropdown");
    dropdown.innerHTML = ""; // Clear it first

    if (data.loggedIn) {
      // If user is logged in, show full menu
      dropdown.innerHTML = `
        <li>
          <a href="myAccount.html">
            <img src="assets/images/user.svg" alt="My Account Icon" />
            My Account
          </a>
        </li>
        <li>
          <a href="myBooking.html">
            <img src="assets/images/bookings.svg" alt="My Bookings Icon" />
            My Bookings
          </a>
        </li>
        <li>
          <a href="addAgency.html">
            <img src="assets/images/become_host.svg" alt="Become Agency Icon" />
            Become an Agency Owner
          </a>
        </li>
        <li>
          <a href="logout.php">
            <img src="assets/images/logout.svg" alt="Logout Icon" />
            Logout
          </a>
        </li>
      `;
    } else {
      // Not logged in → show only Login
      dropdown.innerHTML = `
        <li>
          <a href="login.html">
            <img src="assets/images/user.svg" alt="Login Icon" />
            Login
          </a>
        </li>
      `;
    }
  })
  .catch((err) => {
    console.error("Error checking login:", err);
  });
