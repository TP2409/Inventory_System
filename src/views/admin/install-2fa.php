<!DOCTYPE html>
<html>
<head>
    <title>Two Factor Authentication</title>
    <link rel="stylesheet" href="./css/registration.css">
</head>
<body>
<div>    
    <h3 class="sign-in-2fa">Two-Factor Authentication</h3>
    <form >
        <br>
    <p><strong> Step-1 : Install Google Authenticator </strong><br><br>
     Install Google Authenticator app on your mobile device from <br><br>
        <button type="button" class="btn" onclick="window.open('https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en&gl=US', '_blank')">
            Google Play Store
         </button> 
        or 
        <button type="button" class="btn" onclick="window.open('https://apps.apple.com/us/app/google-authenticator/id388497605', '_blank')">
            Apple App Store
        </button>
    </p><br><br>

    <div class="btn-group">
        <a class="btn" href="index.php?action=setup-2fa">Next</a>
        <a class="btn" href="index.php?action=cancel-setup-2fa">Cancel Setup</a>
    </div>
</form>
</div>
</body>
</html>