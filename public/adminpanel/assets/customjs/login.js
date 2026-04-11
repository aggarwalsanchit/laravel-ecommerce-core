// Password show/hide toggle
const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");

if (togglePassword && password) {
    togglePassword.addEventListener("click", function () {
        const type =
            password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);

        const icon = this.querySelector("i");
        if (icon) {
            icon.classList.toggle("ti-eye");
            icon.classList.toggle("ti-eye-off");
        }
    });
}

// Login button disable on submit
const loginForm = document.getElementById("loginForm");
const loginBtn = document.getElementById("loginBtn");
let isSubmitting = false;

if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
        // Prevent multiple submissions
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        // Validate form
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        let hasError = false;

        // Remove existing error messages
        document
            .querySelectorAll(".invalid-feedback")
            .forEach((el) => el.remove());
        document
            .querySelectorAll(".is-invalid")
            .forEach((el) => el.classList.remove("is-invalid"));

        if (!email.value.trim()) {
            showError(email, "Email is required");
            hasError = true;
        }

        if (!password.value) {
            showError(password, "Password is required");
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            return false;
        }

        // Disable button
        isSubmitting = true;
        loginBtn.disabled = true;

        // Change button text and show spinner
        const originalHtml = loginBtn.innerHTML;
        loginBtn.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Logging in...';

        // Store original HTML to restore on error
        loginBtn.setAttribute("data-original-html", originalHtml);

        // Allow form to submit
        return true;
    });
}

// Show error function
function showError(input, message) {
    input.classList.add("is-invalid");
    const errorDiv = document.createElement("div");
    errorDiv.className = "invalid-feedback d-block";
    errorDiv.innerText = message;
    input.parentNode.parentNode.appendChild(errorDiv);
}
