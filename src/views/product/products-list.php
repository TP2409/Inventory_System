<!DOCTYPE html>
<?php include __DIR__ . "/../layouts/header.php"; ?>
<?php include __DIR__ . "/../layouts/sidebar.php"; ?>
<html>
    <head>
        <title> Product List </title>
        <link rel="stylesheet"  href="./css/style.css">
    </head>

<body>   
<h2>Products List</h2><br>
<div>
   <h4>Total Products: <?= $totalProducts ?></h4>
</div>
<div class="right-bar">
    <a href="index.php?action=product-add" class="btn">Add New Product</a>
    <a href="index.php?action=invoice-create" class="btn">Create Invoice</a>
    <a href="index.php?action=admin-logout"class="btn" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
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
    <h4>Low Stock Products</h4>
    <?php if (!empty($lowStockProducts)): ?>
        <table>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Quantity</th>
            </tr>
            <?php foreach ($lowStockProducts as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['category']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No low stock products</p>
    <?php endif; ?>
</body> 
</html>
<?php include __DIR__ . "/../layouts/footer.php"; ?>