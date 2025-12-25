<html>
    <head>
        <title>Invoice </title>
        <link rel="stylesheet"  href="./css/style.css">
    </head>

<body>   
<h2>Invoice</h2>

<form method="POST" action="index.php?action=invoice-store">
    <table>
        <tr>
            <th>Select</th>
            <th>Product</th>
            <th>Quantity</th>
        </tr>

        <?php foreach ($products as $product): ?>
        <tr>
            <td>
                <input type="checkbox" name="products[<?= $product['id'] ?>][id]" value="<?= $product['id'] ?>">
            </td>
            <td>
                <?= $product['name'] ?> 
            </td>
            <td>
                <input type="number"
                       name="products[<?= $product['id'] ?>][qty]"
                       value="1"
                       min="1"
                       max="<?= $product['quantity'] ?>">
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <button class="btn" type="submit" >Generate Invoice</button>
    <button class="btn" type="button" onclick="window.location.href='index.php?action=products-list'">Cancel</button>
</form>
</body>
</html>