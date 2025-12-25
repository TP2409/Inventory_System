<?php
namespace App\Models;

use App\Database;
use PDO;

class Invoice
{
   private PDO $db; 

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM invoices ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $stmt =$this->db->prepare("INSERT INTO invoices(invoice_no, total_amount,gst_amount,final_amount)
        VALUES (:invoice_no, :total_amount, :gst_amount,:final_amount)");

         $stmt->execute([
         ':invoice_no' => $data['invoice_no'],
         ':total_amount' => $data['total_amount'],
         ':gst_amount'=> $data['gst_amount'] ,
         ':final_amount'=> $data['final_amount']
         ]);

         return $this->db->lastInsertId();

    }

    public function insertItems($invoiceId, $items)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO invoice_items (invoice_id, product_id, quantity, price, total)
             VALUES (:invoice_id, :product_id, :quantity, :price, :total)"
        );

        foreach ($items as $item) {
            $stmt->execute([
                ':invoice_id'=>$invoiceId,
                ':product_id'=>$item['product_id'],
                ':quantity'=>$item['quantity'],
                ':price'=>$item['price'],
                ':total'=>$item['total']
            ]);
        }
    }

    public function getInvoiceWithItems($invoiceId)
    {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE id = ?");
        $stmt->execute([$invoiceId]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$invoice) {
            return null;
        }

        $stmt = $this->db->prepare("
            SELECT ii.*, p.name AS product_name
            FROM invoice_items ii
            JOIN products p ON p.id = ii.product_id
            WHERE ii.invoice_id = ?
        ");
        $stmt->execute([$invoiceId]);

        $invoice['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $invoice;
    }

    public function getTotalInvoices(){
        $stmt=$this->db->query("SELECT COUNT(*) AS total FROM invoices");
        return $stmt->fetch()['total'];
    }


}