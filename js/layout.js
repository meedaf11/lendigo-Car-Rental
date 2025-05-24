 
const hamburgerBtn = document.getElementById("hamburger-btn");
const mainNav = document.querySelector(".mainNav");

 function toggleDropdown() {
    const dropdown = document.querySelector(".user-profile .dropdown");
    dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
  }

  // Optional: Close dropdown when clicking outside
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

document.querySelectorAll(".mainNav ul li a").forEach(link => {
  link.addEventListener("click", () => {
    mainNav.classList.remove("open");
  });
});