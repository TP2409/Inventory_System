<?php
namespace App\Controllers;

use App\Controller;
use App\Models\Product;
use App\Models\Invoice;

class ProductsController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }
    }

    public function index()
    {
        if (!isset($_SESSION['admin_id'])) { header("Location: index.php?action=admin-login"); exit; }

        $productModel = new Product();
        $invoiceModel = new Invoice();
        $products = $productModel->getAll();
        $totalProducts = $productModel->getTotalCount();
        $lowStockProducts = $productModel->getLowStockCount(5);
        $totalInvoices = $invoiceModel->getTotalInvoices();

        $this->view("/product/products-list", [
            "products"=>$products,
            "totalProducts" => $totalProducts,
            "lowStockProducts" => $lowStockProducts,
            "totalInvoices" => $totalInvoices
        ]);    
    
    }

    public function add()
    {
        $this->view("/product/product-add");
    }

    public function store()
    {
        $model = new Product();

        $imageName = 'default.png';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

            $uploadDir = __DIR__ . '/../../public/upload/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = time() . '_' . uniqid() . '.' . $ext;

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $uploadDir . $imageName
            );
        }

        $data = [
            "name" => $_POST['name'],
            "category" => $_POST['category'],
            "quantity" => $_POST['quantity'],
            "price" => $_POST['price'],
            "description" => $_POST['description'],
            "image" => $imageName 
        ];

        $model->insert($data);
        header("Location: index.php?action=products-list");
        exit;
    }


    public function editForm()
    {
        $id = $_GET['id'];
        $model = new Product();
        $product = $model->find($id);
        $this->view("/product/product-edit", ["product"=>$product]);
    }

    public function update()
    {
        $id = $_POST['id'];
        $model = new Product();

        $product = $model->find($id);
        $oldImage = $product['image'];

        $imageName = $oldImage;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

            $uploadDir = __DIR__ . '/../../public/upload/';

            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = time() . '_' . uniqid() . '.' . $ext;

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $uploadDir . $imageName
            );

            if ($oldImage && file_exists($uploadDir . $oldImage)) {
                unlink($uploadDir . $oldImage);
            }
        }

        $data = [
            "name" => $_POST['name'],
            "category" => $_POST['category'],
            "quantity" => $_POST['quantity'],
            "price" => $_POST['price'],
            "description" => $_POST['description'],
            "image" => $imageName
        ];

        $model->update($id, $data);

        $this->checkStock($id);

        header("Location: index.php?action=products-list");
        exit;
    }


    public function delete()
    {
        $id = $_GET['id'];
        $model = new Product();
        $model->delete($id);
        header("Location: index.php?action=products-list");
        exit;
    }

    public function updateStockForm()
    {
        $id = $_GET['id'];
        $product = (new Product())->find($id);
        $this->view("/product/product-stock-update", ["product"=>$product]);
    }

    public function updateStock()
    {
        if (!isset($_POST['id'], $_POST['quantity'])) {
            die("ID or Quantity missing");
        }

        $id = (int) $_POST['id'];
        $quantity = (int) $_POST['quantity'];

        $model = new Product();
        $model->updateStock($id, $quantity);

        $this->checkStock($id);
        error_log("Stock updated for product ID: $id");

        header("Location: index.php?action=products-list");
        exit;
        
    }
    private function checkStock($id)
    {
        $model = new Product();
        $product = $model->find($id);

        if ((int)$product['quantity'] === 0) {
            $this->stockAlert($product['name'], $product['quantity']);
        }
    }

    public function stockAlert($productName,$quantity){

        error_log("Stock alert for $productName");
    
        $subject = "Product out of stoke.";
        $toEmail = "tishapatel249@gmail.com"; 
        $fromEmail = "tishapatel249@gmail.com";
        
    
        $body = "Hello Admin,\n\nYour product is out of stoke!\n\n"
        ."Product: " . $productName. "\n"
        ."Quantity: ". $quantity ."\n\n"
        ."Please restoke the product\n";
        
        $headers = "From: " . $fromEmail . "\r\n";

        if (mail($toEmail, $subject, $body, $headers)){
            error_log("Mail send value TRUE");
        } else {
            error_log("Mail send value FALSE");

        }
            

    }

    public function lowStockList()
    {
        if (!isset($_SESSION['admin_id'])) { header("Location: index.php?action=low-stock-list"); exit; }

        $productModel = new Product();
        $invoiceModel = new Invoice();
        $products = $productModel->getAll();
        $lowStockList =$productModel->getLowStocks();
        $totalProducts = $productModel->getTotalCount();
        $lowStockProducts = $productModel->getLowStockCount(5);
        $totalInvoices = $invoiceModel->getTotalInvoices();

        $this->view("/product/low-stock-list", [
            "products"=>$products,
            "lowStockList"=>$lowStockList,
            "totalProducts" => $totalProducts,
            "lowStockProducts" => $lowStockProducts,
            "totalInvoices" => $totalInvoices
        ]);    
    
    }

    

}
