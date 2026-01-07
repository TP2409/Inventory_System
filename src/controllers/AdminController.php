<?php
namespace App\Controllers;

use App\Controller;
use App\Models\Admin;
use RobThree\Auth\TwoFactorAuth;
use Google\Client as Google;
use Google\Service\Oauth2;

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

        if ($admin['login_provider'] == 'google') {
            header("Location: index.php?action=admin-login&error=google_only");
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
    public function editProfile()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin-profile");
            exit;
        }

        $model = new Admin();
        $this->view("/admin/admin-profile-update", [
            'admin' => $model->find($_SESSION['admin_id'])
        ]);
        exit;
    }
    public function updateProfile()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }

        $id = $_SESSION['admin_id'];
        $model = new Admin();

        $data = [
            'name'  => $_POST['name'],
            'email' => $_POST['email']
        ];

        // update password if provided
        if (!empty($_POST['password'])) {
            $rawPassword = $_POST['password']; 
            $hashedPassword = password_hash($rawPassword, PASSWORD_BCRYPT);
            $data['password'] = $hashedPassword;
        }

        $model->update($id, $data); 

        $email = $data['email'];
        $name = $data['name'];
        $subject = "Updated your profile Successfully";
        $toEmail = $data['email']; 
        $fromEmail = "tishapatel249@gmail.com";


        $body = "Hello " . $name . ",\n\nYour profile has been updated successfully!\n\n"
        ."Here are your updated details:\n"
        ."Email: " . $email . "\n"
        ."Password: ". $rawPassword ."\n\n"
        ."Please keep this information safe.\n";
        
        $headers = "From: " . $fromEmail . "\r\n";

        if (mail($toEmail, $subject, $body, $headers)) {
            echo "<div class='alert alert-success'>Profile Update successful! Email sent to " . $email. "</div>";
        } else {
            echo "<div class='alert alert-warning'>Profile Update successful, but could not send email. (Check WAMP mail settings)</div>";
        }

        header("Location: index.php?action=admin-profile");
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

        $model = new Admin();
        $admin = $model->find($_SESSION['setup_2fa_user']);

        $to = $admin['email'];
        $subject = "Two-Factor Authentication Enabled";
        $fromEmail = "tishapatel249@gmail.com";
        $headers = "From: " . $fromEmail . "\r\n";

        $message = "2FA Enabled Successfully\n\n"
        ."Two-Factor Authentication has been enabled on your account.\n\n"
        ."Time: ".date('d M Y, h:i A') . "\n\n";


        mail($to, $subject, $message, $headers);

    
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
            header("Location: index.php?action=admin-login");
            exit;
        }

        (new Admin())->disable2fa($_SESSION['admin_id']);
        $model = new Admin();
        $admin = $model->find($_SESSION['admin_id']);

        $to = $admin['email'];
        $subject = "Two-Factor Authentication Disabled";
        $fromEmail = "tishapatel249@gmail.com";
        $headers = "From: " . $fromEmail . "\r\n";

        $message = "2FA Disabled Successfully\n\n"
        ."Two-Factor Authentication has been disabled on your account.\n\n"
        ."Time: ".date('d M Y, h:i A') . "\n\n";


        mail($to, $subject, $message, $headers);

        header("Location: index.php?action=products-list&msg=2fa-disabled");
        exit;
    }
    public function reset2fa()
    {
        $model = new Admin();

        /* case 1: (verify-2fa page) */
        if (isset($_SESSION['temp_admin_id'])) {

            $adminId = $_SESSION['temp_admin_id'];

            $model->reset2fa($adminId);

            $model->logSecurityAction($adminId, '2FA_RESET_LOST_DEVICE');

            unset($_SESSION['temp_admin_id']);

            $_SESSION['setup_2fa_user'] = $adminId;

            header("Location: index.php?action=setup-2fa&msg=2fa-reset");
            exit;
        }

        /*case 2:(confirm-2fa page)*/
        if (isset($_SESSION['setup_2fa_user'])) {

            $adminId = $_SESSION['setup_2fa_user'];

            unset($_SESSION['setup_2fa_user'], $_SESSION['2fa_secret_temp']);

            $model->reset2fa($adminId);

            $model->logSecurityAction($adminId, '2FA_RESET_CANCELLED');

            $_SESSION['admin_id'] = $adminId;

            header("Location: index.php?action=admin-profile&msg=setup-cancelled");
            exit;
        }

        header("Location: index.php?action=admin-login");
        exit;
    }
    public function googleLogin()
    {
        $client = new Google();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setRedirectUri(GOOGLE_REDIRECT_URL);
        $client->addScope('email');
        $client->addScope('profile');

        header("Location: " . $client->createAuthUrl());
        exit;
    }

    public function googleCallback()
    {
        if (!isset($_GET['code'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }

        $client = new Google();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URL);
        $client->addScope('email');
        $client->addScope('profile');

        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if (isset($token['error'])) {
            die("TOKEN ERROR");
        }

        $client->setAccessToken($token);

        $oauth = new Oauth2($client);
        $user = $oauth->userinfo->get();

        $email    = $user->email;
        $name     = $user->name;
        $googleId = $user->id;

        $model = new Admin();
        $admin = $model->findByEmail($email);

        if (!$admin) {
            $adminId = $model->insertGoogleUser([
                'name'           => $name,
                'email'          => $email,
                'google_id'      => $googleId,
                'login_provider' => 'google'
            ]);
        } else {
            $adminId = $admin['id'];
        }

        if (!$adminId) {
            die("ADMIN ID NOT CREATED");
        }

        $_SESSION['admin_id'] = $adminId;

        header("Location: index.php?action=products-list");
        exit;
    }
}