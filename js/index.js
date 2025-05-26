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




