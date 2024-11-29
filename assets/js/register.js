$(document).ready(function () {
    // Toggle between login and register forms
    $("#hideLogin").on("click", function () {
        $("#loginForm").hide();
        $("#registerForm").show();
    });

    $("#hideRegister").on("click", function () {
        $("#registerForm").hide();
        $("#loginForm").show();
    });
});