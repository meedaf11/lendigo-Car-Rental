fetch("../pages/header.html")
  .then((response) => response.text())
  .then((data) => {
    document.getElementById("header-placeholder").innerHTML = data;

    // 🔁 إعادة تحميل سكريبت layout.js بعد إدخال الهيدر
    const script = document.createElement("script");
    script.src = "../js/layout.js";
    document.body.appendChild(script);
  })
  .catch((error) => {
    console.error("There was a problem loading the header:", error);
  });
