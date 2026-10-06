window.switchAuth = function (mode) {
    const slider = document.getElementById("auth-slider");
    const tabLogin = document.getElementById("tab-login");
    const tabSignup = document.getElementById("tab-signup");

    const formLogin = document.getElementById("form-login");
    const formSignup = document.getElementById("form-signup");

    if (mode === "login") {
        slider.style.transform = "translateX(0%)";
        tabLogin.classList.add("active");
        tabSignup.classList.remove("active");
        formLogin.classList.remove("hidden-panel");
        formLogin.classList.add("fade-enter");
        formSignup.classList.add("hidden-panel");
        formSignup.classList.remove("fade-enter");
    } else {
        slider.style.transform = "translateX(100%)";
        tabSignup.classList.add("active");
        tabLogin.classList.remove("active");
        formSignup.classList.remove("hidden-panel");
        formSignup.classList.add("fade-enter");
        formLogin.classList.add("hidden-panel");
        formLogin.classList.remove("fade-enter");
    }
};

window.selectRole = function (roleString) {
    // We no longer need the integer mapping!
    // Just pass the string directly into the hidden input for Spatie.
    document.getElementById("role-input").value = roleString;

    // Update Visual Styling
    const buttons = document.querySelectorAll(".role-btn");
    buttons.forEach((btn) => btn.classList.remove("active"));

    const activeBtn = document.getElementById("role-btn-" + roleString);
    if (activeBtn) {
        activeBtn.classList.add("active");
    }
};
