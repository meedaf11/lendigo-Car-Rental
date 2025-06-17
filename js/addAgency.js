document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("addAgencyForm");
    const tabs = document.querySelectorAll(".tab");
    const tabButtons = document.querySelectorAll(".tab-btn");
    const nextButtons = document.querySelectorAll(".next-btn");
    const prevButtons = document.querySelectorAll(".prev-btn");
    const summaryAmount = document.getElementById("summaryAmount");

    // Form data storage
    const agencyData = {
        agency: {},
        car: {},
        payment: {}
    };

    function switchTab(tabId) {
        tabs.forEach(tab => tab.classList.remove("active"));
        tabButtons.forEach(btn => btn.classList.remove("active"));

        const targetTab = document.getElementById(tabId);
        const targetBtn = document.querySelector(`[data-tab="${tabId}"]`);

        if (targetTab) {
            targetTab.classList.add("active");
        }

        // Only try to activate the tab button if it exists
        if (targetBtn) {
            targetBtn.classList.add("active");
        }

        // Pre-fill payment suggestion
        if (tabId === "paymentTab") {
            const carPrice = parseFloat(document.getElementById("carPrice").value);
            if (!isNaN(carPrice) && carPrice > 0) {
                const minPayment = Math.ceil(carPrice * 0.10);
                document.getElementById("paymentAmount").placeholder = `Minimum: ${minPayment} DH`;
                summaryAmount.innerText = `${minPayment} DH`;
            }
        }
    }


    const stripe = Stripe('pk_test_51QnVHRG278XaJnqxLnI0O2LhX29NlknG5827TOqBy6krhTOaSaa43md7ReiY78akJdPG2mAOqF71J3koJ064iaDR00B59FXxgs'); // Replace with your real test/live key
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element'); // You’ll add this to your HTML below


    function validateFields(fields) {
        let isValid = true;
        let emptyCount = 0;

        fields.forEach(field => {
            const input = document.getElementById(field);
            const message = input.parentElement.querySelector(".validation-message");

            if (!input.value.trim()) {
                emptyCount++;
            }

            if (!input.checkValidity()) {
                input.classList.add("input-error");
                message.style.display = "block";
                isValid = false;
            } else {
                input.classList.remove("input-error");
                message.style.display = "none";
            }
        });

        // If all fields are empty
        if (emptyCount === fields.length) {
            alert("All fields are empty. Please fill in the required information.");
            return false;
        }

        // Additional logic for payment tab
        if (fields.includes("paymentAmount")) {
            const paymentInput = document.getElementById("paymentAmount");
            const carPrice = parseFloat(document.getElementById("carPrice").value);
            const minPayment = Math.ceil(carPrice * 0.10); // rounded up for clarity
            const actualPayment = parseFloat(paymentInput.value);

            if (isNaN(carPrice) || carPrice <= 0) {
                alert("Please enter a valid car price first.");
                return false;
            }

            if (actualPayment < minPayment) {
                alert(`Minimum registration fee must be at least 10% of daily price: ${minPayment} DH`);
                paymentInput.classList.add("input-error");
                return false;
            }
        }

        return isValid;
    }


    function collectData(tab) {
        if (tab === "agencyTab") {
            agencyData.agency = {
                name: agencyName.value,
                email: agencyEmail.value,
                phone: agencyPhone.value,
                city: agencyCity.value,
                location: agencyLocation.value,
                description: agencyDescription.value,
                image: agencyImage.value
            };
        } else if (tab === "carTab") {
            agencyData.car = {
                name: carName.value,
                brand: carBrand.value,
                model: carModel.value,
                type: carType.value,
                places: parseInt(carPlaces.value),
                price: parseFloat(carPrice.value),
                fuel: carFuel.value,
                gear: parseInt(carGear.value),
                kilometers: parseFloat(carKm.value),
                image: carImage.value
            };
        } else if (tab === "paymentTab") {
            const amount = parseFloat(paymentAmount.value);
            agencyData.payment.amount = amount;
            summaryAmount.innerText = `${amount} DH`;
        }
    }

    // Handle "Next Step" buttons
    nextButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const currentTab = btn.closest(".tab").id;
            const nextTab = btn.dataset.next;

            let fieldsToValidate = [];
            if (currentTab === "agencyTab") {
                fieldsToValidate = ["agencyName", "agencyEmail", "agencyPhone", "agencyCity", "agencyLocation", "agencyDescription", "agencyImage"];
            } else if (currentTab === "carTab") {
                fieldsToValidate = ["carName", "carBrand", "carModel", "carType", "carPlaces", "carPrice", "carFuel", "carGear", "carKm", "carImage"];
            }

            if (validateFields(fieldsToValidate)) {
                collectData(currentTab);
                switchTab(nextTab);
            }
        });
    });

    // Handle "Previous" buttons
    prevButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const prevTab = btn.dataset.prev;
            switchTab(prevTab);
        });
    });

    // Handle form submission
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        if (!validateFields(["paymentAmount"])) return;

        collectData("paymentTab");

        const amount = agencyData.payment.amount;

        try {
            // 1. Request PaymentIntent from your backend
            const res = await fetch("create-payment-intent.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ amount: amount })
            });

            const { clientSecret, error } = await res.json();

            if (error) {
                alert("Failed to initialize payment: " + error);
                return;
            }

            // 2. Confirm the payment
            const result = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement
                }
            });

            if (result.error) {
                document.getElementById("card-errors").textContent = result.error.message;
            } else if (result.paymentIntent.status === "succeeded") {
                console.log("Payment successful!", result.paymentIntent);

                console.log("📝 Collected Form Data:", agencyData);

                // Save to DB after payment
                try {
                    const saveRes = await fetch("api/save-agency.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(agencyData)
                    });

                    const saveData = await saveRes.json();

                    if (saveData.success) {
                        console.log("✅ Agency saved to DB with ID:", saveData.agency_id);
                        switchTab("successTab");
                    } else {
                        alert("Payment succeeded but failed to save agency: " + saveData.error);
                    }
                } catch (err) {
                    alert("Error saving agency: " + err.message);
                }


                switchTab("successTab");
            }
        } catch (err) {
            alert("Something went wrong: " + err.message);
        }
    });

});
