<?php
namespace App\Models;

use App\Database;
use PDO;

class Product
{
    private PDO $db; 

    public function __construct()
    {
        $this->db = Database::connect();
    }

     public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $stmt =$this->db->prepare("INSERT INTO products(name, category, quantity, price, description, image)
        VALUES (:name, :category, :quantity,:price, :description, :image)");

         $stmt->execute([
         ':name' => $data['name'],
         ':category' => $data['category'],
         ':quantity'=> $data['quantity'] ,
         ':price'=> $data['price'],
         ':description'=> $data ['description'],
         ':image' => $data['image']

         ]);

    }
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE products SET
             name = :name,
             category = :category,
             quantity = :quantity,
             price = :price,
             description = :description,
             image = :image
         WHERE id = :id");

         $stmt->execute([
         'name' => $data['name'],
         'category' => $data['category'],
         'quantity'=> $data['quantity'] ,
         'price'=> $data['price'],
         'description'=> $data ['description'],
         'image'=>$data['image'],
         'id'=>$id
         ]);    
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function updateStock($id, $quantity)
    {
        $stmt = $this->db->prepare(
            "UPDATE products SET quantity = :quantity WHERE id = :id"
        );

        return $stmt->execute([
            'quantity' => $quantity,
            'id' => $id
        ]);
        
    }

    public function updateQuantity($id, $qty)
    {
        $stmt = $this->db->prepare("UPDATE products SET quantity = ? WHERE id = ?");
        return $stmt->execute([$qty, $id]);
    }

    public function getTotalCount(){
        $stmt=$this->db->query("SELECT COUNT(*) AS total FROM products");
        return $stmt->fetch()['total'];
    }

    public function getLowStockCount($limit=5){
        $stmt=$this->db->prepare("SELECT COUNT(*) AS total FROM products WHERE quantity < :qty");
        $stmt->execute(['qty'=> $limit]);
        return $stmt->fetch()['total'];
    }

     public function getLowStocks($limit=5){
        $stmt=$this->db->prepare("SELECT * FROM products WHERE quantity < :qty");
        $stmt->execute(['qty'=> $limit]);
        return $stmt->fetchAll();
    }
    
    
}