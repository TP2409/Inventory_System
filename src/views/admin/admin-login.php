<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="./css/registration.css">
</head>
<body>

<div>
    <h3 class="sign-in">Login</h3>

    <form id="loginForm" method="POST" action="index.php?action=admin-login-check">

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Email">

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Password">

        <button type="submit" class="btn">Login</button>
        <button type="button" class="btn" onclick="window.location.href='index.php?action=admin-register'">
            Register
        </button>

    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function () {
    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            email: "Please enter a valid email",
            password: {
                required: "Please enter your password",
                minlength: "Password must be at least 6 characters"
            }
        },
        errorClass: "error"
    });
});
</script>

</body>
</html>
