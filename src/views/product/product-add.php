<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet"  href="./css/style.css">
</head>

<body>
<h2> Add Product</h2>
<div>
<form id="addProduct" method="POST" action="index.php?action=product-store">
    <label for="name" >Product Name:</label>
    <input type="text" id="name" name="name" placeholder="Product Name" required><br>

    <label for="category" >Category:</label>
    <input type="text" id="category" name="category" placeholder="Category" required><br>

    <label for="quantity" >Quantity:</label>
    <input type="number" id="quantity" name="quantity" placeholder="Quantity" required><br>

    <label for="price" >Price:</label>
    <input type="number" id="price" name="price" placeholder="Price" required><br>

    <label for="description" >Description:</label>
    <input type="textbox" id="description" name="description" placeholder="Description " ><br>

    <label for="image" >Image:</label>
    <input type="file" name="image" accept="image/*" class="form-control" required><br>

    <button type="submit" class="btn" >Save</button>
    <button type="button" class="btn" onclick="window.location.href='index.php?action=products-list'">Cancel</button>

</form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        
        $.validator.addMethod("validQuantity", function(value, element) {
            return this.optional(element) || /^[0-9]{1,3}$/.test(value);
        }, "Please enter a valid quantity number.");

        $.validator.addMethod("validPrice", function(value, element) {
            return this.optional(element) || /^[0-9]{2,5}$/.test(value);
        }, "Please enter a valid price number.");

        $("#addProduct").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                category: {
                    required: true,
                },
                quantity: {
                    required: true,
                    validQuantity: true  
                },
                price: {
                    required: true,
                    validPrice: true
                },
                image: {
                    required:true,
                }
            },
            messages: {
                name: {
                    required: "Please enter your name",
                    minlength: "Name must be at least 2 characters"
                },
                category: {
                    required: "Please enter your category",
                },
                quantity: {
                    required: "Please enter a quantity",
                    validQuantity: "Please enter valid quantity number for product."
                },
                price: {
                    required: "Please enter quantity",
                    validPrice: "Please enter valid price for product."
                },
                image: {
                    required: "Please upload image of product"
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

