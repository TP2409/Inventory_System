<?php
namespace App\Controllers;

use App\Controller;
use App\Models\Admin;
use RobThree\Auth\TwoFactorAuth;

class AdminController extends Controller
{
   
    public function index()
    {
        $this->view("/admin/admin-login");
    }

    public function add()
    {
        $this->view("/admin/admin-register");
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: index.php?action=admin-login");
        exit;
    }

  
    public function loginCheck()
    {
        $email     = $_POST['email'] ?? '';
        $password  = $_POST['password'] ?? '';
        $enable2fa = isset($_POST['enable_2fa']);

        $admin = (new Admin())->findByEmail($email);

        if (!$admin || !password_verify($password, $admin['password'])) {
            header("Location: index.php?action=admin-login&error=1");
            exit;
        }

        if ($admin['google2fa_enabled'] == 1) {
        $_SESSION['temp_admin_id'] = $admin['id'];
        header("Location: index.php?action=verify-2fa");
        exit;
        }
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: index.php?action=products-list");
        exit;
    }
     public function store()
    {
        $model = new Admin();

        $rawPassword = $_POST['password']; 
        $hashedPassword = password_hash($rawPassword, PASSWORD_BCRYPT);

        $model->insert([
            "name" => $_POST['name'],
            "email" => $_POST['email'],
            "password" => $hashedPassword,
            "raw_password" => $rawPassword
        ]);

        header("Location: index.php?action=admin-login");
        exit;
    }

    public function profile()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }

        $model = new Admin();
        $admin = $model->find($_SESSION['admin_id']);

        $this->view("/admin/admin-profile", [
            'admin' => $admin
        ]);
    }
    public function install2fa()
    {

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }

        $_SESSION['setup_2fa_user'] = $_SESSION['admin_id'];

        $this->view("/admin/install-2fa");
    }
    public function setup2fa()
    {
        if (!isset($_SESSION['setup_2fa_user'])) {
            header("Location: index.php?action=admin-profile");
            exit;
        }

        $tfa = new TwoFactorAuth('IMS');

        if (!isset($_SESSION['2fa_secret_temp'])) {
            $_SESSION['2fa_secret_temp'] = $tfa->createSecret();
        }

        $admin = (new Admin())->find($_SESSION['setup_2fa_user']);

        $qrCodeUrl = $tfa->getQRCodeImageAsDataUri(
            $admin['email'],
            $_SESSION['2fa_secret_temp']
        );

        $this->view("/admin/setup-2fa", [
            'qrCodeUrl' => $qrCodeUrl,
            'secret'    => $_SESSION['2fa_secret_temp'] 
        ]);
    }
    public function showConfirm2fa()
    {
        if (!isset($_SESSION['setup_2fa_user'], $_SESSION['2fa_secret_temp'])) {
            header("Location: index.php?action=products-list");
            exit;
        }

        $this->view("/admin/confirm-2fa");
    }
    

    public function confirm2fa()
    {
        if (
            empty($_POST['code']) ||
            !isset($_SESSION['setup_2fa_user'], $_SESSION['2fa_secret_temp'])
        ) {
            header("Location: index.php?action=setup-2fa&error=missing");
            exit;
        }

        $code   = trim($_POST['code']);
        $secret = $_SESSION['2fa_secret_temp'];
        $adminId = $_SESSION['setup_2fa_user'];

        $tfa = new TwoFactorAuth('IMS');

        if (!$tfa->verifyCode($secret, $code, 2)) {
            header("Location: index.php?action=setup-2fa&error=invalid");
            exit;
        }

        $adminModel = new Admin();
        $adminModel->enable2fa($adminId, $secret);

    
        $_SESSION['admin_id'] = $adminId;

        unset($_SESSION['setup_2fa_user'], $_SESSION['2fa_secret_temp']);

        header("Location: index.php?action=admin-profile&msg=2fa-enabled");
        exit;
    }



    public function showVerify2fa()
    {
        if (!isset($_SESSION['temp_admin_id'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }

        $this->view("/admin/verify-2fa");
    }

    public function verify2fa()
    {
        if (!isset($_SESSION['temp_admin_id'])) {
            header("Location: index.php?action=admin-profile");
            exit;
        }

        $code = trim($_POST['code'] ?? '');

        if ($code === '') {
            header("Location: index.php?action=verify-2fa&error=empty");
            exit;
        }

        $admin = (new Admin())->find($_SESSION['temp_admin_id']);
        $tfa   = new TwoFactorAuth('IMS');

        if ($tfa->verifyCode($admin['google2fa_secret'], $code, 2)) {
            $_SESSION['admin_id'] = $admin['id'];
            unset($_SESSION['temp_admin_id']);

            header("Location: index.php?action=products-list");
            exit;
        }

        header("Location: index.php?action=verify-2fa&error=invalid");
        exit;
    }

    
    public function cancelSetup2fa()
    {

        unset($_SESSION['setup_2fa_user'], $_SESSION['2fa_secret_temp']);

        header("Location: index.php?action=admin-profile");
        exit;
    }


    public function disable2fa()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin-profile");
            exit;
        }

        (new Admin())->disable2fa($_SESSION['admin_id']);

        header("Location: index.php?action=products-list&msg=2fa-disabled");
        exit;
    }

}