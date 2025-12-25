<!DOCTYPE html>
<html>
    <head>
        <title>Upadate Product</title>
        <link rel="stylesheet"  href="./css/style.css">
    </head>

<body>
<?php
$product = $product ?? [];
?>

<h1>Edit Product</h1>

<form id="editProduct" method="POST" action="index.php?action=product-update" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= htmlspecialchars($product['id'] ?? '') ?>">
    
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>

    <label for="category">Category:</label>
    <input type="text" name="category" id="category" value="<?= htmlspecialchars($product['category'] ?? '') ?>" required>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($product['quantity'] ?? '') ?>" required>

    <label for="price">Price:</label>
    <input type="number" name="price" id="price" value="<?= htmlspecialchars($product['price'] ?? '') ?>" required>
    
    <label for="description">Description:</label>
    <textarea name="description" id="description" value="<?= htmlspecialchars($product['description'] ?? '') ?>"></textarea>

    <label for="image">Image:</label>
    <?php if (!empty($product['image'])): ?>
        <br>
        <img src="upload/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" width="150">
        <br>
    <?php endif; ?>
    <input type="file" name="image" id="image">

    <button type="submit" class="btn">Update Product</button>
    <button type="button" class="btn" onclick="window.location.href='index.php?action=products-list'">Cancel</button>

</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
       
        $.validator.addMethod("validQuantity", function(value, element) {
            return this.optional(element) || /^[0-9]{1,3}$/.test(value);
        }, "Please enter a valid quantity number.");

        $.validator.addMethod("validPrice", function(value, element) {
            return this.optional(element) || /^[0-9]{2,3}$/.test(value);
        }, "Please enter a valid price number.");

        $("#editProduct").validate({
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