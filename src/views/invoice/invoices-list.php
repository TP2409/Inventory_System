<!DOCTYPE html>
<?php include __DIR__ . "/../layouts/header.php"; ?>
<?php include __DIR__ . "/../layouts/sidebar.php"; ?>
<html>
<head>
    <title>Invoices</title>
    <link rel="stylesheet"  href="./css/style.css">
</head>
<body>

<h2>Generated Invoices</h2>

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
