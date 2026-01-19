<?php
namespace App\Models;

use App\Database;
use PDO;

class Auth
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
        $placeholderPassword = password_hash(uniqid(mt_rand(), true), PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name,email,password,google_id,login_provider)
                VALUES (?,?,?,?,'google')";
        $this->db->prepare($sql)->execute([
            $data['name'],
            $data['email'],
            $placeholderPassword,
            $data['google_id']
        ]);
        return $this->db->lastInsertId();
    }

    public function findUserByMobile($mobile)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE mobile = ?");
        $stmt->execute([$mobile]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveOtp($userId, $otp)
    {
        $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        $stmt = $this->db->prepare("
            INSERT INTO otps (user_id, otp, expires_at)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([$userId, $otp, $expiry]);
    }

    public function verifyOtp($userId, $otp)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM otps
            WHERE user_id = ?
              AND otp = ?
              AND is_used = 0
              AND expires_at > NOW()
            ORDER BY id DESC
            LIMIT 1
        ");

        $stmt->execute([$userId, $otp]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return false;

        $this->db->prepare("UPDATE otps SET is_used = 1 WHERE id = ?")
                 ->execute([$row['id']]);

        return true;
    }
}