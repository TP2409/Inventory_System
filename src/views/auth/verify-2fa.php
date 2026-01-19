<!DOCTYPE html>
<html>
<head>
    <title>2FA Verification</title>
    <link rel="stylesheet" href="./css/registration.css">
</head>
<body>
<div>    
    <h3 class="sign-in">Verify Code</h3>
    
    <form method="POST" action="index.php?action=verify-2fa-check">
        <br>

        <label>Enter 6-digit code from app</label><br>
        <input type="text" name="code" required maxlength="6">

        <br><br>

        <div class="grp-btn">
            <button type="submit" class="btn">Verify</button>
            <a href="index.php?action=reset-2fa" class="btn" onclick="return confirm('Reset 2FA?')">
            Reset or Lost your device
            </a>
       </div>
    </form>
</div>
</body>
</html>
