// تحميل الهيدر والفوتر
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
  const dateDisplay = document.getElementById("date-display");
  let picker = null; // ⬅️ تعريف picker هنا

  if (dateDisplay) {
    picker = new Litepicker({
      element: dateDisplay,
      singleMode: false,
      format: "MMM D",
      minDate: new Date(),
      numberOfMonths: 2,
      numberOfColumns: 2,
      setup: (pickerInstance) => {
        pickerInstance.on("selected", (start, end) => {
          if (start && end) {
            const startFormatted = start.format("MMM D");
            const endFormatted = end.format("MMM D");
            dateDisplay.textContent = `${startFormatted} - ${endFormatted}`;
          }
        });
      },
    });

    dateDisplay.textContent = "Choose Date";
  }

  // ⚡️ عند الضغط على زر البحث
  document.querySelector(".searchBtn").addEventListener("click", () => {
    const brand = document.getElementById("brand").value;
    const model = document.getElementById("model").value;
    const city = document.getElementById("city").value;

    // التحقق من وجود picker قبل استخدامه
    const startDate = picker?.getStartDate()?.format("YYYY-MM-DD") || null;
    const endDate = picker?.getEndDate()?.format("YYYY-MM-DD") || null;

    const filters = {
      brand,
      model,
      city,
      startDate,
      endDate,
    };

    console.log("All filters:", filters);
  });
});

function showTab(tabId) {
  document
    .querySelectorAll(".content")
    .forEach((c) => c.classList.remove("active"));
  document.getElementById(tabId).classList.add("active");

  document
    .querySelectorAll(".tab")
    .forEach((t) => t.classList.remove("active_tab"));
  if (tabId === "car") {
    document.querySelectorAll(".tab")[0].classList.add("active_tab");
  } else {
    document.querySelectorAll(".tab")[1].classList.add("active_tab");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  let data;

  fetch("reviews.json")
    .then((response) => response.json())
    .then((json) => {
      data = json;
      loadContent("carReviews", "car");
      loadContent("agencyReviews", "agency");
    });

  function loadContent(type, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";
    data[type].forEach((item) => {
      const card = document.createElement("div");
      card.className = "card";
      card.innerHTML = `
          <div class="reviewInfo">
          <img src="assets/images/quote.svg" alt="" />
          <h3>${item.name} </h3>
          <span class="rating">${item.rating} ⭐</span>
          </div>
          
          <p>${item.text}</p>
          
          <button class="btn">${item.button}</button>
        `;
      container.appendChild(card);
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {

  // Variable to hold FAQ data after fetching
let faqData = [];

// Fetch FAQ data from JSON file
fetch('faq.json')
  .then(response => {
    if (!response.ok) {
      throw new Error(`Failed to fetch file: ${response.status}`);
    }
    return response.json();
  })
  .then(data => {
    faqData = data;
    renderFAQ(); // Render FAQs after successful fetch
  })
  .catch(error => {
    console.error('Error loading FAQs:', error);
    const container = document.getElementById('faq-container');
    container.innerHTML = `<p>Unable to load FAQs. Please try again later.</p>`;
  });

// Render FAQ items into the page
function renderFAQ() {
  const container = document.getElementById('faq-container');
  container.innerHTML = ''; // Clear any existing content

  faqData.forEach((item, index) => {
    // Create the main FAQ item container
    const faqItem = document.createElement('div');
    faqItem.classList.add('item');
    faqItem.setAttribute('data-index', index);

    // Create question element
    const questionEl = document.createElement('div');
    questionEl.classList.add('question');
    questionEl.textContent = item.question;

    // Create answer element
    const answerEl = document.createElement('div');
    answerEl.classList.add('answer');
    answerEl.textContent = item.answer;

    // Toggle “active” class on click to show/hide answer
    questionEl.addEventListener('click', () => {
      faqItem.classList.toggle('active');
    });

    // Append question and answer to the item container
    faqItem.appendChild(questionEl);
    faqItem.appendChild(answerEl);

    // Append the item container to the FAQ wrapper
    container.appendChild(faqItem);
  });
}



})
