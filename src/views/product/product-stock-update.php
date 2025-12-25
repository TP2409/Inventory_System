<!DOCTYPE html>
<html>
<head>
    <title>Update Stock</title>
    <link rel="stylesheet"  href="./css/style.css">
</head>
<body>


    <h2>Stock Update</h2>
    <form id="restockProduct" method="POST" action="index.php?action=product-restock">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <label>Product: <?= $product['name'] ?></label><br>

        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required><br>
        
        <button type="submit" class="btn">Update Stock</button>
        <button type="button" class="btn" onclick="window.location.href='index.php?action=products-list'">Cancel</button>

    </form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        
        $.validator.addMethod("validQuantity", function(value, element) {
            return this.optional(element) || /^[0-9]{1,3}$/.test(value);
        }, "Please enter a valid quantity number.");


         $("#restockProduct").validate({
            rules: {
                quantity: {
                    required: true,
                    validQuantity: true  
                },
            },
            messages: {
                quantity: {
                    required: "Please enter a quantity",
                    validQuantity: "Please enter valid quantity number for product."
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