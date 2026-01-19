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
        $mobile = $_POST['mobile'] ?? '';
        if (!$mobile) {
            die("Mobile required");
        }

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
}