# **Inventory Management System**

## **Project Overview**

The Inventory Management System is a PHP-based web application developed using the MVC architecture.  
The system is designed to help administrators manage products, monitor stock levels, and handle invoices efficiently.

This project focuses on inventory tracking, low-stock monitoring, and basic invoice management, making it suitable for learning purposes.


---


## **Objectives**

- To manage products and inventory efficiently
- To track product stock levels in real time
- To identify low-stock products
- To generate and manage invoices
- To implement MVC architecture using PHP


---


## **Technology Stack**

- Frontend: HTML, CSS, JavaScript  
- Backend: PHP  
- Database: MySQL  
- Server: WAMP Server
- 2FA Google Authentication 
- Architecture: MVC (Model–View–Controller)


---


## **System Architecture (MVC)**

### **Model**
- Handles database operations
- Examples:
  - 'Product.php'
  - 'Invoice.php'
  - 'Admin.php'

### **View**
- Responsible for UI and presentation
- Examples:
  - Product list
  - Invoice list
  - Low Stock Product list
  - Total Counts of Lists

### **Controller**
- Acts as an intermediary between Model and View
- Handles user requests and business logic
- Examples:
  - 'ProductsController.php'
  - 'InvoiceController.php'
  - 'AdminController.php'


---


## **Functional Modules**

### **Admin Module**
- Admin login
- Admin logout
- Admin Profile details
- Enable and Disable the 2fa authentication
- Session-based authentication

### **Product Module**
- Add new products
- Edit existing products
- Delete products
- Upload product images
- View all products

### **Stock Management Module**
- Update product quantity
- Highlight low-stock products
- Display low-stock count on dashboard
- Restock the product items

### **Invoice Module**
- Create invoices
- View invoice list
- View individual invoices
- Track total number of invoices

### **Dashboard Module**
- Admin Profile
- Products List
- Invoice List
- Low Stock List
- Total products count
- Low stock products count
- Total invoices count


---


## **Screenshots**
### **Dashboard**
<p align="center">
  <img width="1920" height="954" alt="Main Dashboard" src="https://github.com/user-attachments/assets/0fca47e6-a6c6-45de-8941-3f0dbf7bb137" /><br>
  <strong>Figure 1: Main Dashboard</strong>
</p>
<p align="center">
  <img width="1920" height="949" alt="Invoices" src="https://github.com/user-attachments/assets/c522268c-a66d-4d84-b0bd-a148f38eb464" /><br>
  <strong>Figure 2: Invoice List</strong>
</p>
<p align="center">
  <img width="1920" height="950" alt="Low Stock" src="https://github.com/user-attachments/assets/7d0191a2-0147-45fc-9ddf-ffaa88b57150" /><br>
  <strong>Figure 3: Low Stock List</strong>
</p>


### **Product List View**
<p align="center">
  <img width="1920" height="954" alt="Main Dashboard" src="https://github.com/user-attachments/assets/0fca47e6-a6c6-45de-8941-3f0dbf7bb137" />
  <strong>Figure 4: Product List </strong>
</p>
<p align="center" >
  <img width="1920" height="640" alt="Add Product " src="https://github.com/user-attachments/assets/7b42f37f-c5be-48e3-b511-cc8dec8f85b3" />
  <strong>Figure 5: Add New Product</strong>
</p>
<p align="center">
  <img width="1920" height="600" alt="Create Invoice" src="https://github.com/user-attachments/assets/29438865-9b87-44f8-9012-5945f3d40bfd" />
  <strong>Figure 6: Create Invoice </strong>
</p>
<p align="center">
  <img width="1920" height="957" alt="Logout " src="https://github.com/user-attachments/assets/cd5612fa-73a8-41c6-9cc7-8cf8a63f217d" />
  <strong>Figure 7: Logout Session</strong>
</p>

#### **Actions**
<p align="center">
  <img width="1920" height="768" alt="Edit Products" src="https://github.com/user-attachments/assets/bf0420d8-bdc4-4748-a629-4c2ded4ca29e" />
  <strong>Figure 8: Edit Product Details</strong>
</p>
<p align="center">  
  <img width="1920" height="964" alt="Delete Product " src="https://github.com/user-attachments/assets/f18a089d-0574-4e81-80ac-01dcd692e02f" />
  <strong>Figure 9: Delete Product </strong>
</p>
<p align="center">
  <img width="1920" height="295" alt="Restock" src="https://github.com/user-attachments/assets/9f826fb5-7912-4d63-8417-16a56b71255f" />
  <strong>Figure 10: Restock Products</strong>
</p>

### **Invoice List View**
<p align="center">
<img width="1920" height="949" alt="Invoices" src="https://github.com/user-attachments/assets/117ab8d5-65c4-4fd2-982a-4c9e8c2993fe" />
<strong>Figure 11: Invoice List </strong>
</p>
<p align="center">
<img width="1920" height="502" alt="Invoice" src="https://github.com/user-attachments/assets/be0c14f5-6cce-4b4e-8deb-76ed7ad4f436" />
<strong>Figure 12: View the individual incoice that are generated</strong>
</p>
  
### **Low Stock View and Alerts**
<p align="center">
<img width="1920" height="950" alt="Low Stock " src="https://github.com/user-attachments/assets/7d0191a2-0147-45fc-9ddf-ffaa88b57150" />
<strong>Figure 13: Low Stock List </strong>
</p>
<p align="center">
<img width="1920" height="295" alt="Restock" src="https://github.com/user-attachments/assets/e8e1329e-85c0-4560-835e-39610b447f95" />
<strong>Figure 14: Restock the products that have low stock</strong>
</p>
<p align="center">
<img width="1920" height="942" alt="Restock Mail " src="https://github.com/user-attachments/assets/eec35e82-bada-44a0-8253-41332521bd06" />
<strong>Figure 15: Restock Mail when quantity is zero. </strong>
</p>

### **Login and Registration **
<p align="center">
<img width="1920" height="346" alt="Login" src="https://github.com/user-attachments/assets/9688c885-e28e-4c9f-8417-cee57e7299ca" />
<strong>Figure 16: Login </strong>
</p>
<p align="center">
<img width="1920" height="482" alt="Registration" src="https://github.com/user-attachments/assets/2a6aaee3-57e4-4cfc-94f1-58e0749e64f5" />
<strong>Figure 17: Register if new user </strong>
</p>
<p align="center">
<img width="1920" height="643" alt="Registration Mail" src="https://github.com/user-attachments/assets/5c7336d3-e5b6-498c-81d3-67a0c80625d2" />
<strong>Figure 18: Registration Successful mail with username and password </strong> 
</p>

### **Admin Profile**
<p align="center">
  <img width="1920" height="954" alt="Admin Profile" src="https://github.com/user-attachments/assets/1b44ef68-3fc4-43cb-8711-b297e3b070ce" />
  <strong>Figure 19: Admin Profile </strong>>
</p>



