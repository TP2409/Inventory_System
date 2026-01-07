<?php
namespace App\Models;

use App\Database;
use PDO;
use RobThree\TwoFactorAuth\lib\TwoFactorAuth;

class Admin
{
    private PDO $db; 

    public function __construct()
    {
        $this->db = Database::connect();
    }

     public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function insert($data)
    {
        
        $rawPassword = $data['password'];
        $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

        $stmt =$this->db->prepare("INSERT INTO users(name,email,password)
        VALUES (:name, :email, :password)");

        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
        ]);

        $email = $data['email'];
        $rawPassword = $data['raw_password'];
        $subject = "Registration Successful";
        $toEmail = $data['email']; 
        $fromEmail = "tishapatel249@gmail.com";
        
    
        $body = "Welcome,\n\nYour registration was successful!\n\n"
        ."Here are your login details:\n"
        ."Email: " . $email . "\n"
        ."Password: ". $rawPassword ."\n\n"
        ."Please keep this information safe.\n";
        
        $headers = "From: " . $fromEmail . "\r\n";

        if (mail($toEmail, $subject, $body, $headers)) {
            echo "<div class='alert alert-success'>Registration successful! Email sent to " . $email. "</div>";
        } else {
            echo "<div class='alert alert-warning'>Registration successful, but could not send email. (Check WAMP mail settings)</div>";
        }
        
        echo "<script> setTimeout(function() { window.location.href = 'index.php?success=created'; }, 2000); </script>";
        exit;  

    }

    public function update($id, $data)
    {
        $sql = "UPDATE users SET name = :name, email = :email";

        if (isset($data['password'])) {
            $sql .= ", password = :password";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $params = [
            ':name'  => $data['name'],
            ':email' => $data['email'],
            ':id'    => $id
        ];

        if (isset($data['password'])) {
            $params[':password'] = $data['password'];
        }

        return $stmt->execute($params);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function enable2fa($adminId, $secret)
    {
        $sql = "UPDATE users 
                SET google2fa_secret = ?, 
                    google2fa_enabled = 1 
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$secret, $adminId]);        
    }

    public function disable2fa($id)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET google2fa_secret = NULL, google2fa_enabled = 0 
            WHERE id = :id
        ");
        return $stmt->execute([':id' => $id]);
    }

    public function reset2fa($adminId)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET google2fa_secret = NULL, google2fa_enabled = 0 
            WHERE id = ?
        ");
        return $stmt->execute([$adminId]);
    }

    public function logSecurityAction($adminId, $action)
    {
        $stmt = $this->db->prepare("
            INSERT INTO security_logs (admin_id, action, ip_address, user_agent)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $adminId,
            $action,
            $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN',
            $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN'
        ]);
    }

    public function insertGoogleUser($data)
    {
        $sql = "INSERT INTO users (name,email,google_id,login_provider)
                VALUES (?,?,?,'google')";
        $this->db->prepare($sql)->execute([
            $data['name'],
            $data['email'],
            $data['google_id'],
            $data['login_provider']
        ]);
        return $this->db->lastInsertId();
    }
}