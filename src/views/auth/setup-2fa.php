<!DOCTYPE html>
<html>
<head>
    <title>Two Factor Authentication</title>
    <link rel="stylesheet" href="./css/registration.css">
</head>
<body>
<div>    
    <h3 class="sign-in-2fa">Scan QR Code</h3>
    <form >
        <br>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === '2fa-reset'): ?>
            <div class="alert alert-warning">  
                Please set up Two-Factor Authentication again using a new QR code.
            </div>
        <?php endif; ?>
  
        <p><strong> Step-2 : Set up your account </strong><br><br>
        Open the app and scan the QR code on the screen to add your account.</p><br><br>
        <img src="<?= $qrCodeUrl ?>" alt="QR Code">

        <p hidden>Secret Key: <b><?= $_SESSION['2fa_secret_temp'] ?></b></p><br><br>

        <div class="btn-group">
            <a class="btn" href="index.php?action=confirm-2fa">Next</a>
            <a class="btn" href="index.php?action=cancel-setup-2fa">Cancel Setup</a>
        </div>
</form>
</div>
</body>
</html>