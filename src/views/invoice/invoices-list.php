<!DOCTYPE html>
<?php include __DIR__ . "/../layouts/header.php"; ?>
<?php include __DIR__ . "/../layouts/sidebar.php"; ?>
<html>
<head>
    <title>Invoices</title>
    <link rel="stylesheet"  href="./css/style.css">
</head>
<body>
<div class="grid">
    <a class="card-btn" href="index.php?action=products-list"><h4>Total Products: <?= $totalProducts ?></h4></a>
    <a class="card-btn" href="index.php?action=invoices-list"><h4>Total Invoices: <?= $totalInvoices ?></h4></a>
    <a class="card-btn" href="index.php?action=low-stock-list"><h4>Low Stock Products: <?= $lowStockProducts ?></h4></a>
</div> <br>

<h2>Generated Invoices</h2>

<div class="right-bar">
    <a href="index.php?action=invoice-create" class="btn">Create Invoice</a>
</div>

<table width="100%">
<tr>
    <th>Invoice No</th>
    <th>Total</th>
    <th>GST</th>
    <th>Final</th>
    <th>Action</th>
</tr>

<?php foreach ($invoices as $invoice): ?>
<tr>
    <td><?= $invoice['invoice_no'] ?></td>
    <td><?= $invoice['total_amount'] ?></td>
    <td><?= $invoice['gst_amount'] ?></td>
    <td><?= $invoice['final_amount'] ?></td>
    <td>
        <a class="btn"
           href="index.php?action=invoice-view&id=<?= $invoice['id'] ?>">
           View
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>


</body>
</html>
<?php include __DIR__ . "/../layouts/footer.php"; ?>
