document.addEventListener("DOMContentLoaded", function() {
    const pass = document.getElementById("pass");
    const togglePassword = document.getElementById("togglePassword");

    togglePassword.addEventListener("click", function() {
      const type = pass.getAttribute("type") === "password" ? "text" : "password";
      pass.setAttribute("type", type);
      
      if (type === "text") {
        togglePassword.classList.remove('fa-eye-slash');
        togglePassword.classList.add('fa-eye');
      } else {
        togglePassword.classList.add('fa-eye-slash');
        togglePassword.classList.remove('fa-eye');
      }
    });
  });