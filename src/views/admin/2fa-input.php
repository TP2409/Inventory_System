<!DOCTYPE html>
<html>
<head>
    <title>2FA Verification</title>
    <link rel="stylesheet" href="./css/registration.css">
</head>
<body>
<div>    
    <h3 class="sign-in">Verify Code</h3>

    <p><strong> Step-3 : Verify Code </strong><br><br>
    
    <form method="POST" action="index.php?action=confirm-2fa">

        <label>Enter 6-digit code from app</label><br>
        <input type="text" name="code" required maxlength="6">

        <br><br>

        <button type="submit" class="btn">
            Verify & Enable 2FA
        </button>

    </form>
</div>
</body>
</html>
