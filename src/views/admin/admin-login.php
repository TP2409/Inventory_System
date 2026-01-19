<?php
use Google\Client as Google;

$client = new Google();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URL);
$client->addScope('email');
$client->addScope('profile');

$loginUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="./css/registration.css">
</head>
<body>

<div>
    <br>  
    <form id="loginForm" method="POST" action="index.php?action=admin-login-check">
        <h3>Login</h3>
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Email">

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Password">

        <div class="grp-btn">
            <button type="submit" class="btn">Login</button>
            <button type="button" class="btn" onclick="window.location.href='index.php?action=admin-register'">Register</button>
        </div><br>
        <div class="btns">
            <a href="<?= htmlspecialchars($loginUrl) ?>" class="google-btn">
            <img src="https://developers.google.com/identity/images/g-logo.png">
             Login with Google
            </a><br><br>
            <button type="button" onclick="window.location.href='index.php?action=mobile-login'" class="google-btn" >Login with Mobile Number</button>
        </div>
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
