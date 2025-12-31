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
    public function loginCheck() {

        $email    = $_POST['email'];
        $password = $_POST['password'];
        $enable2fa = isset($_POST['enable_2fa']);

        $model = new Admin();
        $admin = $model->findByEmail($email);

        if (!$admin || !password_verify($password, $admin['password'])) {
             header("Location: index.php?action=admin-login&error=1");
              exit;
        }

        //case-1: already enable 2fa
        if ($admin['google2fa_enabled'] == 1) {
            $_SESSION['temp_admin_id'] = $admin['id'];
            header("Location: index.php?action=verify-2fa");
            exit;
        }

        //case-2: enable 2fa during login
        if ($enable2fa) {
            $_SESSION['setup_2fa_user'] = $admin['id'];
            header("Location: index.php?action=setup-2fa");
            exit;
        }

        //case-3: normal login
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

    
    public function setup2fa()
    {

        if (!isset($_SESSION['setup_2fa_user'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }

        $tfa = new TwoFactorAuth('IMS');
        $secret = $tfa->createSecret();

    
        $_SESSION['2fa_secret_temp'] = $secret;

        $adminModel = new Admin();
        $admin = $adminModel->find($_SESSION['setup_2fa_user']);

        $qrCodeUrl = $tfa->getQRCodeImageAsDataUri(
            $admin['email'],
            $secret
        );

        $this->view("/admin/setup-2fa", [
            'qrCodeUrl' => $qrCodeUrl,
            'secret'    => $secret
        ]);
    }

    public function confirm2fa()
    {
        if (
            empty($_POST['code']) ||
            !isset($_SESSION['setup_2fa_user'], $_SESSION['2fa_secret_temp'])
        ) {
            header("Location: index.php?action=setup-2fa&error=missing-code");
            exit;
        }

        $code   = trim($_POST['code']);
        $secret = $_SESSION['2fa_secret_temp'];

        $tfa = new TwoFactorAuth();

        if (!$tfa->verifyCode($secret, $code)) {
            header("Location: index.php?action=setup-2fa&error=invalid-code");
            exit;
        }

        $model = new Admin();
        $model->enable2fa($_SESSION['setup_2fa_user'], $secret);

        $_SESSION['admin_id'] = $_SESSION['setup_2fa_user'];

        unset($_SESSION['setup_2fa_user'], $_SESSION['2fa_secret_temp']);

        header("Location: index.php?action=products-list");
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
            header("Location: index.php?action=admin-login");
            exit;
        }

        if (empty($_POST['code'])) {
            header("Location: index.php?action=verify-2fa&error=empty");
            exit;
        }

        $model = new Admin();
        $admin = $model->find($_SESSION['temp_admin_id']);

        $tfa = new TwoFactorAuth('IMS');

        if ($tfa->verifyCode($admin['google2fa_secret'], trim($_POST['code']), 2)) {
            $_SESSION['admin_id'] = $admin['id'];
            unset($_SESSION['temp_admin_id']);

            header("Location: index.php?action=products-list");
            exit;
        }

        header("Location: index.php?action=verify-2fa&error=invalid");
        exit;
    }  
    
    public function disable2fa()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }

        $model = new Admin();
        $model->disable2fa($_SESSION['admin_id']);

        header("Location: index.php?action=products-list&msg=2fa-disabled");
        exit;
    }

    public function cancelSetup2fa()
    {
        if (!isset($_SESSION['setup_2fa_user'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }

        unset($_SESSION['setup_2fa_user'], $_SESSION['2fa_secret_temp']);

        
        $_SESSION['admin_id'] = $_SESSION['setup_2fa_user'] ?? null;

        header("Location: index.php?action=products-list");
        exit;
    }
    
}





