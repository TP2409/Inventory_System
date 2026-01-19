<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/google_config.php';
require __DIR__ . '/../config/Twilio_config.local.php';

use App\Controllers\ProductsController;
use App\Controllers\AdminController;
use App\Controllers\InvoiceController;
use App\Controllers\AuthController;

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

    case "mobile-login":    
        (new AuthController())->mobileLogin();
        break;

    case "send-mobile-otp": 
        (new AuthController())->sendMobileOtp();
        break;
        
    case "verify-mobile-otp":
        $_SERVER['REQUEST_METHOD'] === 'POST' 
        ? (new AuthController())->verifyMobileOtp()
        : (new AuthController())->verifyMobileOtpPage();
        break;    

    case "admin-logout":
        (new AdminController())->logout();
        break;   

    case"google-callback":
        (new AuthController())->googleCallback();
        break;    

    case "install-2fa":
        (new AuthController())->install2fa();
        break;
    
    case "setup-2fa":
        (new AuthController())->setup2fa();
        break;

    case "confirm-2fa":
        $_SERVER['REQUEST_METHOD'] === 'POST' 
        ? (new AuthController())->confirm2fa()
        : (new AuthController())->showConfirm2fa();
        break;

    case "disable-2fa":
        (new AuthController())->disable2fa();
        break;  

    case "reset-2fa":
        (new AuthController())->reset2fa();
        break;

    case "verify-2fa":
        (new AuthController())->showVerify2fa();
        break;

    case "verify-2fa-check":
         (new AuthController())->verify2fa();
        break;

    case "cancel-setup-2fa":
        (new AuthController())->cancelSetup2fa();
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

    case "low-stock-list": 
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
