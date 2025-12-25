<?php
namespace App\Controllers;

use App\Controller;
use App\Models\Product;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }
    }


    public function index()
    {
        if (!isset($_SESSION['admin_id'])) { header("Location: index.php?action=invoices-list"); exit; }

        $productModel = new Product();
        $invoiceModel = new Invoice();
        $products = $productModel->getAll();
        $invoices =$invoiceModel->getAll();
        $totalProducts = $productModel->getTotalCount();
        $lowStockProducts = $productModel->getLowStockCount(5);
        $totalInvoices = $invoiceModel->getTotalInvoices();

        $this->view("/invoice/invoices-list", [
            "products"=>$products,
            "invoices"=>$invoices,
            "totalProducts" => $totalProducts,
            "lowStockProducts" => $lowStockProducts,
            "totalInvoices" => $totalInvoices
        ]); 
    }


    public function createForm()
    {
        $products = (new Product())->getAll();
        $this->view("/invoice/invoice-create", ["products"=>$products]);
    }

    public function store()
    {
        if (!isset($_POST['products'])) {
            header("Location: index.php?action=invoice-create");
            exit;
        }

        $productsData = $_POST['products'];

        $total = 0;
        $items = [];

        $productModel = new Product();

        foreach ($productsData as $item) {

            if (!isset($item['id'])) continue; 

            $productId = (int)$item['id'];
            $qty       = (int)$item['qty'];

            if ($qty <= 0) continue;

            $product = $productModel->find($productId);

            if (!$product) continue;

            if ($qty > $product['quantity']) {
                die("Not enough stock for product: " . $product['name']);
            }

            $lineTotal = $product['price'] * $qty;
            $total += $lineTotal;

            $items[] = [
                'product_id' => $productId,
                'quantity'   => $qty,
                'price'      => $product['price'],
                'total'      => $lineTotal
            ];

            $newQty = $product['quantity'] - $qty;
            $productModel->updateQuantity($productId, $newQty);
        }

        if ($total == 0) {
            die("No products selected");
        }

        $gst = $total * 0.18;
        $finalAmount = $total + $gst;
        $invoiceNo = "INV" . time();

        $invoiceModel = new Invoice();
        $invoiceId = $invoiceModel->insert([
            'invoice_no'   => $invoiceNo,
            'total_amount' => $total,
            'gst_amount'   => $gst,
            'final_amount' => $finalAmount
        ]);

        $invoiceModel->insertItems($invoiceId, $items);

        header("Location: index.php?action=invoice-view&id=" . $invoiceId);
        exit;
    }


    public function show()
    {
        $id = $_GET['id'];
        $invoice = (new Invoice())->getInvoiceWithItems($id);
        $this->view("/invoice/invoice-view", compact("invoice"));
    }
}
