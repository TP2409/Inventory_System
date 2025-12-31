<!DOCTYPE html>
<html>
<head>
    <title> Confirm-2fa</title>
    <link rel="stylesheet" href="./css/registration.css">
</head>
<body>
<div>    
    <h3 class="sign-in">Verify Code</h3>
    <form method="POST" action="index.php?action=verify-2fa">
        <br>
        <p><strong> Step-3 : Verify Code </strong><br><br>

        <label>Enter 6-digit code from app</label><br>
        <input type="text" name="code" required maxlength="6">

        <br><br>

        <button type="submit" class="btn">
            Verify 
        </button>

        <a class="btn" href="index.php?action=cancel-setup-2fa">
            Cancel or Lost your device
        </a>

    </form>
</div>
</body>
</html>