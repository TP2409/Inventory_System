<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/google_config.php';

use App\Controllers\ProductsController;
use App\Controllers\AdminController;
use App\Controllers\InvoiceController;

$action = $_GET['action'] ?? 'admin-login';

switch ($action) {

    case "admin-login":
        (new AdminController())->index();
        break;

    case"google-login":
        (new AdminController())->googleLogin();
        break;

    case"google-callback":
        (new AdminController())->googleCallback();
        break;

    case "admin-register":
        (new AdminController())->add();
        break;

    case "admin-store":
        (new AdminController())->store();
        break;
        
    case "admin-profile":
        (new AdminController())->profile();
        break;

    case "admin-profile-edit":
        (new AdminController())->editProfile();
        break;

    case "admin-profile-update":
        (new AdminController())->updateProfile();
        break;  
        
    case "admin-login-check":
        (new AdminController())->loginCheck();
        break;

    case "admin-logout":
        (new AdminController())->logout();
        break;

    case 'install-2fa':
        (new AdminController())->install2fa();
        break;
    
    case 'setup-2fa':
        (new AdminController())->setup2fa();
        break;

    case "confirm-2fa":
        $_SERVER['REQUEST_METHOD'] === 'POST' 
        ? (new AdminController())->confirm2fa()
        : (new AdminController())->showConfirm2fa();
        break;

    case "disable-2fa":
        (new AdminController())->disable2fa();
        break;  

    case "reset-2fa":
        (new AdminController())->reset2fa();
        break;

    case 'verify-2fa':
        (new AdminController())->showVerify2fa();
        break;

    case 'verify-2fa-check':
         (new AdminController())->verify2fa();
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
