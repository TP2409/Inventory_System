<?php
namespace App\Controllers;

use App\Controller;
use App\Models\Admin;

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

        $model = new Admin();
        $admin = $model->findByEmail($_POST['email']);

        if ($admin && password_verify($_POST['password'], $admin['password'])) {

            $_SESSION['admin_id'] = $admin['id'];

            header("Location: index.php?action=products-list");
            exit;
        }

        header("Location: index.php?action=admin-login&error=1");
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



}