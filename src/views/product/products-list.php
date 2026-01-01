<!DOCTYPE html>
<?php include __DIR__ . "/../layouts/header.php"; ?>
<?php include __DIR__ . "/../layouts/sidebar.php"; ?>
<html>
    <head>
        <title> Product List </title>
        <link rel="stylesheet"  href="./css/style.css">
    </head>

<body>
<div class="grid">
    <a class="card-btn" href="index.php?action=products-list"><h4>Total Products: <?= $totalProducts ?></h4></a>
    <a class="card-btn" href="index.php?action=invoices-list"><h4>Total Invoices: <?= $totalInvoices ?></h4></a>
    <a class="card-btn" href="index.php?action=low-stock-list"><h4>Low Stock Products: <?= $lowStockProducts ?></h4></a>
</div> 

<h2>Products List</h2>

<div class="right-bar">
    <a href="index.php?action=product-add" class="btn">Add New Product</a>
    <a href="index.php?action=invoice-create" class="btn">Create Invoice</a>
</div>
    <table>
        <thead> 
            <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th>
             </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
            <tr style="background-color: <?= ($product['quantity'] < 5) ? '#f8d7da' : '' ?>">
                <td><?= $product['name'] ?></td>
                <td><?= $product['category'] ?></td>
                <td><?= $product['quantity'] ?></td>
                <td><?= $product['price'] ?></td>
                <td><?= $product['description'] ?></td>
                <td> <img src="upload/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" width="150"></td>
                
                <td>
                    <a class="action-btn edit" href="index.php?action=product-edit&id=<?= $product['id'] ?>">Edit</a>
                    <a class="action-btn delete" 
                        onclick="return confirm('Are you sure you want to delete?')"
                        href="index.php?action=product-delete&id=<?= $product['id'] ?>">
                        Delete
                    </a>
                    <a class="action-btn btn restock" href="index.php?action=product-stock-update&id=<?= $product['id'] ?>">Restock</a>
                </td>

            </tr> 
            <?php endforeach ?>   
        </tbody>
    </table><br>
    
</body> 
</html>
<?php include __DIR__ . "/../layouts/footer.php"; ?>