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
