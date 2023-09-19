const password = document.getElementById("password");
const confirmPassword = document.getElementById("password-confirm");
const message = document.getElementById("message");

confirmPassword.addEventListener("input", function () {
    if (password.value === confirmPassword.value) {
        message.innerHTML = "Le password corrispondono!";
        message.style.color = "green";
    } else {
        message.innerHTML = "Le password non corrispondono!";
        message.style.color = "red";
    }
});