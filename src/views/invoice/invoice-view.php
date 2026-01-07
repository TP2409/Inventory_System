<!DOCTYPE html>
<?php include __DIR__ . "/../layouts/header.php"; ?>
<?php include __DIR__ . "/../layouts/sidebar.php"; ?>
<html>
<head>
    <title>Invoice View</title>
    <link rel="stylesheet"  href="./css/style.css">
</head>
<body>

<h2>Invoice</h2><br>

<p><b>Invoice No:</b> <?= $invoice['invoice_no'] ?></p><br>
<p><b>Date:</b> <?= $invoice['created_at'] ?></p><br>

<table width="100%">
<tr>
    <th>Product</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Total</th>
</tr>

<?php foreach ($invoice['items'] as $item): ?>
<tr>
    <td><?= $item['product_name'] ?></td>
    <td><?= $item['quantity'] ?></td>
    <td><?= $item['price'] ?></td>
    <td><?= $item['total'] ?></td>
</tr>
<?php endforeach; ?>
</table><br>

<p><b>Subtotal:</b> <?= $invoice['total_amount'] ?></p><br>
<p><b>GST:</b> <?= $invoice['gst_amount'] ?></p><br>
<p><b>Final:</b> <?= $invoice['final_amount'] ?></p><br>

<p>
    <button class="btn" onclick="window.print()"> Print Invoice</button>
    <button class="btn" type="button" onclick="window.location.href='index.php?action=invoices-list'">Cancel</button>
</p>



</body>
</html>
<?php include __DIR__ . "/../layouts/footer.php"; ?>
