// =======================
// Dark Mode Toggle
// =======================

const themeBtn = document.getElementById("themeBtn");

themeBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark");

    if(document.body.classList.contains("dark")){
        themeBtn.textContent = "Light Mode";
    } else {
        themeBtn.textContent = "Dark Mode";
    }
});


// =======================
// Click Counter
// =======================

let count = 0;

const countDisplay = document.getElementById("count");
const increaseBtn = document.getElementById("increaseBtn");
const resetBtn = document.getElementById("resetBtn");

increaseBtn.addEventListener("click", () => {
    count++;
    countDisplay.textContent = count;
});

resetBtn.addEventListener("click", () => {
    count = 0;
    countDisplay.textContent = count;
});


// =======================
// Form Validation
// =======================

const form = document.getElementById("contactForm");
const message = document.getElementById("message");

form.addEventListener("submit", function(e){
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();

    if(name === "" || email === ""){
        message.textContent = "Please fill all fields.";
        message.style.color = "red";
        return;
    }

    const emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!emailPattern.test(email)){
        message.textContent = "Enter a valid email.";
        message.style.color = "red";
        return;
    }

    message.textContent = "Form submitted successfully!";
    message.style.color = "green";

    form.reset();
});