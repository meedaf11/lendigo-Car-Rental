function animateValue(element, start, end, duration, suffix = "") {
  const startTimestamp = performance.now();
  const step = (timestamp) => {
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    const current =
      suffix === "★"
        ? (start + progress * (end - start)).toFixed(1)
        : Math.floor(progress * (end - start) + start);

    element.textContent = suffix ? `${current}${suffix}` : current;

    if (progress < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}

window.addEventListener("load", () => {
  setTimeout(() => {
    const totalAgencies = parseInt(
      document.getElementById("totalAgencies").textContent
    );
    const activeAgencies = parseInt(
      document.getElementById("activeAgencies").textContent
    );
    const blockedAgencies = parseInt(
      document.getElementById("blockedAgencies").textContent
    );
    const averageRating = parseFloat(
      document.getElementById("averageRating").textContent
    );
    const totalBalance = parseFloat(
      document.getElementById("totalBalance").textContent
    );

    animateValue(
      document.getElementById("totalAgencies"),
      0,
      totalAgencies,
      2000
    );
    animateValue(
      document.getElementById("activeAgencies"),
      0,
      activeAgencies,
      2000
    );
    animateValue(
      document.getElementById("blockedAgencies"),
      0,
      blockedAgencies,
      2000
    );
    animateValue(
      document.getElementById("averageRating"),
      0,
      averageRating,
      2000,
      "★"
    );
    animateValue(
      document.getElementById("totalBalance"),
      0,
      totalBalance,
      2500,
      " MAD"
    );
  }, 500);
});

// Add intersection observer for cards animation
const cards = document.querySelectorAll(".stat-card");
const observerOptions = {
  threshold: 0.1,
  rootMargin: "0px 0px -50px 0px",
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = "1";
      entry.target.style.transform = "translateY(0)";
    }
  });
}, observerOptions);

cards.forEach((card) => {
  observer.observe(card);
});

// Add subtle parallax effect
window.addEventListener("scroll", () => {
  const scrolled = window.pageYOffset;
  const parallaxElements = document.querySelectorAll(".stat-card");

  parallaxElements.forEach((element, index) => {
    const speed = 0.02;
    const yPos = -(scrolled * speed * (index + 1));
    element.style.transform = `translateY(${yPos}px)`;
  });
});

// Add hover sound effect (optional)
cards.forEach((card) => {
  card.addEventListener("mouseenter", () => {
    // Optional: Add subtle sound effect or haptic feedback
    card.style.transform = "translateY(-8px) scale(1.02)";
  });

  card.addEventListener("mouseleave", () => {
    card.style.transform = "translateY(0) scale(1)";
  });
});

document.querySelectorAll(".agency-row").forEach((row) => {
  row.addEventListener("click", () => {
    const agencyId = row.dataset.agencyId;
    fetch(`get_agency_details.php?agency_id=${agencyId}`)
      .then((res) => res.text())
      .then((data) => {
        document.getElementById("agencyDetails").innerHTML = data;
        document.getElementById("agencyModal").classList.remove("hidden");
      });
  });
});

document.querySelector(".close-btn").addEventListener("click", () => {
  document.getElementById("agencyModal").classList.add("hidden");
});

