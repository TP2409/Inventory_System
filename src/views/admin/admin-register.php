<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet"  href="./css/registration.css">
    
</head>
<body>
    <div >
        <h3 class="sign-in">Register</h3>
        <form id="registerForm" method="POST" action="index.php?action=admin-store">
            <br>

            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Name" required><br>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required><br>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required><br>

            <button type="submit" class="btn">Submit</button>
            <button type="button" class="btn" onclick="window.location.href='index.php?action=admin-login'">Cancel</button>

        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script>
    $(document).ready(function () {

        $.validator.addMethod("strongPassword", function(value, element) {
            return this.optional(element) 
                || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value);
        }, "Password must include at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.");

        $.validator.addMethod("validName", function(value, element) {
            if (value.length > 0 && value.length < 2) return false;
            return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
        }, "Only letters and spaces allowed.");

        $("#registerForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    validName: true
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8,
                    strongPassword: true
                }
            },
            messages: {
                name: {
                    required: "Please enter your name",
                    minlength: "Name must be at least 2 characters",
                    validName: "Only letters allowed."
                },
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please enter a password",
                    minlength: "Password must be at least 8 characters",
                    strongPassword: "Include one uppercase, lowercase, number, and special character."
                }
            },
            errorElement: "div",
            errorClass: "error",
            submitHandler: function (form) {
                form.submit();
            }
        });

    });
    </script>


</body>
</html>
