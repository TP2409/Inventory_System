<!DOCTYPE html>
<?php include __DIR__ . "/../layouts/header.php"; ?>
<?php include __DIR__ . "/../layouts/sidebar.php"; ?>
<html>
<head>
    <title>Low Stock</title>
    <link rel="stylesheet"  href="./css/style.css">
</head>
<body>
<div class="grid">
    <a class="card-btn" href="index.php?action=products-list"><h4>Total Products: <?= $totalProducts ?></h4></a>
    <a class="card-btn" href="index.php?action=invoices-list"><h4>Total Invoices: <?= $totalInvoices ?></h4></a>
    <a class="card-btn" href="index.php?action=low-stock-list"><h4>Low Stock Products: <?= $lowStockProducts ?></h4></a>
</div> <br>

<h2>Low Stock Products</h2>

<table>
        <thead> 
            <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Action</th>
             </tr>
        </thead>
        <tbody>
            <?php foreach($lowStockList as $product): ?>
            <tr style="background-color: <?= ($product['quantity'] < 5) ? '#f8d7da' : '' ?>">
                <td><?= $product['name'] ?></td>
                <td><?= $product['quantity'] ?></td>
                <td><?= $product['price'] ?></td>
                <td>
                    <a class="action-btn btn restock" href="index.php?action=product-stock-update&id=<?= $product['id'] ?>">Restock</a>
                </td>

            </tr> 
            <?php endforeach ?>   
        </tbody>
</table><br>

</body>
</html>
<?php include __DIR__ . "/../layouts/footer.php"; ?>