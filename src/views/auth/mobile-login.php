<!DOCTYPE html>
<html>
<head>
    <title>Mobile Login</title>
    <link rel="stylesheet" href="./css/registration.css">
</head>
<body>
<div>    
    <br>
    <form id="mobileNumberForm" method="POST" action="index.php?action=send-mobile-otp" >
        <h3>Mobile Login Verification</h3><br>
        <input type="number" id="mobile" name="mobile" placeholder="+91xxxxxxxxxx">
        <button type="submit" class="btn">Send OTP</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
       
        $.validator.addMethod("validNumber", function(value, element) {
            return this.optional(element) || /^(?:\+91|91)?[6-9][0-9]{9}$/.test(value);
        }, "Please enter a valid phone number.");

        $("#mobileNumberForm").validate({
            rules: {
                mobile: {
                    required: true,
                    validNumber: true
                }
            },
            messages: {
                mobile: {
                    required: "Please enter your mobile number",
                    validNumber: "Enter valid 10 digit mobile number"
                },
            }   
        });
    });
</script>

</body>
</html>