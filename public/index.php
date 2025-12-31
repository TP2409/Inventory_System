<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ProductsController;
use App\Controllers\AdminController;
use App\Controllers\InvoiceController;

$action = $_GET['action'] ?? 'admin-login';

switch ($action) {

    case "admin-login":
        (new AdminController())->index();
        break;

    case "admin-register":
        (new AdminController())->add();
        break;

    case "admin-store":
        (new AdminController())->store();
        break;

    case "admin-login-check":
        (new AdminController())->loginCheck();
        break;

    case "admin-logout":
        (new AdminController())->logout();
        break;

    case 'verify-2fa':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new AdminController())->verify2fa();
        } else {
            (new AdminController())->showVerify2fa();
        }
        break;

    case 'setup-2fa':
        (new AdminController())->setup2fa();
        break;

    case "confirm-2fa":
        (new AdminController())->confirm2fa();
        break;

    case "disable-2fa":
        (new AdminController())->disable2fa();
        break;  
          
    case "cancel-setup-2fa":
        (new AdminController())->cancelSetup2fa();
        break;

    case "products-list":
        (new ProductsController())->index();
        break;

    case "product-add":
        (new ProductsController())->add();
        break;

    case "product-store":
        (new ProductsController())->store();
        break;

    case "product-edit":
        (new ProductsController())->editForm();
        break;

    case "product-update":
        (new ProductsController())->update();
        break;

    case "product-delete":
        (new ProductsController())->delete();
        break;

    case "product-stock-update":
        (new ProductsController())->updateStockForm();
        break;

    case "product-restock":
        (new ProductsController())->updateStock();
        break;  

    case  "low-stock-list": 
        (new ProductsController())->lowStockList();
        break;

    case "invoices-list":
        (new InvoiceController())->index();
        break;

    case "invoice-create":
        (new InvoiceController())->createForm();
        break;

    case "invoice-store":
        (new InvoiceController())->store();
        break;
    
    case "invoice-view":
        (new InvoiceController())->show();
        break;

    default:
    echo "404 - Page Not Found";
}
