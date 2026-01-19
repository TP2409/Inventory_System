<?php
namespace App\Controllers;

use App\Controller;
use App\Models\Auth;
use RobThree\Auth\TwoFactorAuth;
use Google\Client as Google;
use Google\Service\Oauth2;
use App\Services\SmsServices;

class AuthController extends Controller
{
    public function mobileLogin()
    {
        $this->view("/auth/mobile-login");
    }

    public function sendMobileOtp()
    {

        $mobile = $_POST['mobile'] ?? '';

        if (!$mobile) {
            die("Mobile number required");
        }

        $auth = new Auth();
        $user = $auth->findUserByMobile($mobile);

        if (!$user) {
            die("Mobile number not registered");
        }

        $sms = new SmsServices();
        
        if (!$sms->sendOtp($mobile)) {
            die("OTP could not be sent. Try again.");
        }

        $_SESSION['otp_mobile'] = $mobile;
        $_SESSION['otp_user']   = $user['id'];

        header("Location: index.php?action=verify-mobile-otp");
        exit;
    }

    public function verifyMobileOtpPage()
    {
        $this->view ("/auth/verify-mobile-otp");
    }

    public function verifyMobileOtp(): void
    {
        
        if (!isset($_SESSION['otp_user'], $_SESSION['otp_mobile'])) {
            header("Location: index.php?action=mobile-login");
            exit;
        }

        $otp     = trim($_POST['otp'] ?? '');
        $mobile  = $_SESSION['otp_mobile'];
        $userId  = $_SESSION['otp_user'];

        if ($otp === '') {
            die("OTP required");
        }

        $sms = new SmsServices();

        if (!$sms->verifyOtp($mobile, $otp)) {
            die("Invalid or expired OTP");
        }

        $_SESSION['admin_id']  = $userId;
        $_SESSION['logged_in'] = true;

        unset($_SESSION['otp_mobile'], $_SESSION['otp_user']);

        header("Location: index.php?action=products-list");
        exit;
    }

    public function install2fa()
    {

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin-login");
            exit;
        }

        $_SESSION['setup_2fa_user'] = $_SESSION['admin_id'];

        $this->view("/auth/install-2fa");
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

        $admin = (new Auth())->find($_SESSION['setup_2fa_user']);

        $qrCodeUrl = $tfa->getQRCodeImageAsDataUri(
            $admin['email'],
            $_SESSION['2fa_secret_temp']
        );

        $this->view("/auth/setup-2fa", [
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

        $this->view("/auth/confirm-2fa");
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

        $adminModel = new Auth();
        $adminModel->enable2fa($adminId, $secret);

        $model = new Auth();
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

        $this->view("/auth/verify-2fa");
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

        $admin = (new Auth())->find($_SESSION['temp_admin_id']);
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

        (new Auth())->disable2fa($_SESSION['admin_id']);
        $model = new Auth();
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
        $model = new Auth();

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
            echo("TOKEN ERROR:"). $token(['token description']);
        }

        $client->setAccessToken($token);

        $oauth = new Oauth2($client);
        $user = $oauth->userinfo->get();

        $email = $user->email;
        $name = $user->name;
        $password = $user->password;
        $googleId = $user->id;

        $model = new Auth();
        $admin = $model->findByEmail($email);

        if (!$admin) {
            $adminId = $model->insertGoogleUser([
                'name'=> $name,
                'email'=> $email,
                'password'=>$password,
                'google_id'=> $googleId,
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



