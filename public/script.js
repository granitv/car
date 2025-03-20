document.addEventListener("DOMContentLoaded", loadMakes);

async function loadMakes() {
    let res = await fetch("/cars");
    let data = await res.json();
    
    let makeSelect = document.getElementById("make");
    makeSelect.innerHTML = data.map(car => `<option value="${car.make}">${car.make}</option>`).join("");
    loadModels();
}

function loadModels() {
    let selectedMake = document.getElementById("make").value;
    fetch("/cars")
        .then(res => res.json())
        .then(data => {
            let car = data.find(c => c.make === selectedMake);
            let modelSelect = document.getElementById("model");
            modelSelect.innerHTML = car.models.map(m => `<option value="${m}">${m}</option>`).join("");

            let yearSelect = document.getElementById("year");
            yearSelect.innerHTML = car.years.map(y => `<option value="${y}">${y}</option>`).join("");
        });
}

async function selectCar() {
    let carSection = document.getElementById("car-section").style.display = "none";
    let make = document.getElementById("make").value;
    let model = document.getElementById("model").value;
    let year = document.getElementById("year").value;
    let type = document.getElementById("type").value;

    let res = await fetch("/select-car", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ make, model, year, type })
    });

    let data = await res.json();
    if (data.message) {
        document.getElementById("glass-section").style.display = "block";
        loadGlassOptions();
    }
}

async function loadGlassOptions() {
    let res = await fetch("/glass-options");
    let data = await res.json();
    
    let glassSelect = document.getElementById("glass");
    glassSelect.innerHTML = data.glass_options.map(g => `<option value="${g}">${g}</option>`).join("");
}

async function selectGlass() {
    let glassSection = document.getElementById("glass-section").style.display = "none";
    let glassType = document.getElementById("glass").value;

    let res = await fetch("/select-glass", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ glass_type: glassType })
    });

    let data = await res.json();
    if (data.vendors) {
        document.getElementById("vendor-section").style.display = "block";
        let vendorSelect = document.getElementById("vendor");
        vendorSelect.innerHTML = data.vendors.map(v => 
            `<option value="${v.vendor}" data-price="${v.price}" data-time="${v.delivery_time}" data-warranty="${v.warranty}" data-delivery-price="${v.delivery_price}">
                ${v.vendor} | Price: $${v.price} | Warranty: ${v.warranty} | (Delivery time: ${v.delivery_time}) 
            </option>`
        ).join("");
    }
}

async function requestQuote() {
    let vendorSelect = document.getElementById("vendor");
    let selectedVendor = vendorSelect.options[vendorSelect.selectedIndex];

    let res = await fetch("/request-quote", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            vendor_name: selectedVendor.value,
            price: selectedVendor.getAttribute("data-price"),
            delivery_price: selectedVendor.getAttribute("data-delivery-price"),
            delivery_time: selectedVendor.getAttribute("data-time"),
            warranty: selectedVendor.getAttribute("data-warranty")
        })
    });

    let data = await res.json();
    if (data.quote_details) {
        document.getElementById("quote-section").style.display = "block";
        document.getElementById("quote-result").innerText = 
            `Vendor: ${data.quote_details.vendor}, 
            Final Price: $${data.quote_details.final_price}, 
            Delivery: ${data.quote_details.delivery_time}, 
            Warranty: ${data.quote_details.warranty},
            Glass Type: ${data.quote_details.glass}`;
    }
}
