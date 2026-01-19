<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <link rel="stylesheet" href="./css/registration.css">
    <style>
        #timer { font-weight: bold; color: #ff0000; }
        #message { margin-top: 10px; color: #d00; }
    </style>
</head>
<body>
<div>    
    <br>
    <form method="POST" action="index.php?action=verify-mobile-otp">
        <br>
        <h3>Verify OTP</h3><br>

        <label>OTP Sent to mobile number</label><br>
        <input type="number" name="otp" placeholder="Enter the OTP" maxlength="6"> <br><br>

        <span>Time remaining: <span id="timer">10:00</span></span><br><br>

        <div class="grp-btn">
            <button id="verifyBtn" type="submit" class="btn">Verify</button>
            <button id="resendBtn" class="btn" disabled>Resend OTP</button>

       </div>
    </form>
</div>
<script>
let timerDuration = 600; // 10 min
let timerInterval = null;

// Start OTP timer
function startOtpTimer(seconds) {
    timerDuration = seconds;
    const timerElement = document.getElementById('timer');
    const resendBtn = document.getElementById('resendBtn');
    clearInterval(timerInterval);

    timerInterval = setInterval(() => {
        let minutes = Math.floor(timerDuration / 60);
        let sec = timerDuration % 60;
        timerElement.textContent = `${minutes.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')}`;

        timerDuration--;
        if (timerDuration < 0) {
            clearInterval(timerInterval);
            timerElement.textContent = "00:00";
            resendBtn.disabled = false;
            document.getElementById('message').textContent = "OTP expired! Please resend.";
        }
    }, 1000);
}

// on page load
startOtpTimer(timerDuration);

// Verify OTP
document.getElementById('verifyBtn').addEventListener('click', () => {
    const otp = document.getElementById('otpInput').value;
    const messageDiv = document.getElementById('message');

    if (!otp) {
        messageDiv.textContent = "Please enter the OTP!";
        return;
    }

    fetch('index.php?action=verify-mobile-otp', {
        method: 'POST',
        body: new URLSearchParams({ otp }),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            messageDiv.textContent = "OTP verified! Redirecting...";
            window.location.href = data.redirect;
        } else {
            messageDiv.textContent = data.message;
        }
    });
});

// Resend OTP
document.getElementById('resendBtn').addEventListener('click', () => {
    const mobile = "<?php echo $_SESSION['otp_mobile'] ?? ''; ?>";
    const messageDiv = document.getElementById('message');

    fetch('index.php?action=send-mobile-otp', {
        method: 'POST',
        body: new URLSearchParams({ mobile }),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            messageDiv.textContent = "OTP sent again!";
            startOtpTimer(data.timer);
            document.getElementById('resendBtn').disabled = true;
        } else {
            messageDiv.textContent = data.message;
        }
    });
});
</script>
</body>
</html>

