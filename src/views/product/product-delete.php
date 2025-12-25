<!DOCTYPE html>
<html>
<head>
    <title>Delete Product</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<h2>Delete Product</h2>

<p>Are you sure you want to delete <strong><?= $product['name'] ?></strong>?</p>

<form method="POST" action="index.php?action=product-destroy&id=<?= $product['id'] ?>">
    <button type="submit" class="btn" style="background:#E84118;">Yes, Delete</button>
</form>

<br>
<a href="index.php?action=products-list" class="btn">Cancel</a>

</body>
</html>