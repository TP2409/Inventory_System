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
- Products List
- Invoice List
- Low Stock List
- Total products count
- Low stock products count
- Total invoices count


---


## **Screenshots**
### **Dashboard**
<img width="1920" height="954" alt="Main Dashboard" src="https://github.com/user-attachments/assets/0fca47e6-a6c6-45de-8941-3f0dbf7bb137" />
Figure 1: Main Dashboard

<img width="1920" height="949" alt="Invoices" src="https://github.com/user-attachments/assets/c522268c-a66d-4d84-b0bd-a148f38eb464" />
Figure 2: Invoice List 

<img width="1920" height="950" alt="Low Stock " src="https://github.com/user-attachments/assets/7d0191a2-0147-45fc-9ddf-ffaa88b57150" />
Figure 3: Low Stock List 

### **Product List View**
<img width="1920" height="954" alt="Main Dashboard" src="https://github.com/user-attachments/assets/0fca47e6-a6c6-45de-8941-3f0dbf7bb137" />
Figure 4: Product List 

<img width="1920" height="953" alt="Add Product " src="https://github.com/user-attachments/assets/be95a04f-7f2e-46c9-aa4f-164bc4bc56b3" />
Figure 5: Add New Product

<img width="1920" height="945" alt="Create Invoice" src="https://github.com/user-attachments/assets/865536d1-80bc-4ed0-894c-1f83b455432e" />
Figure 6: Create Invoice 

<img width="1920" height="957" alt="Logout " src="https://github.com/user-attachments/assets/cd5612fa-73a8-41c6-9cc7-8cf8a63f217d" />
Figure 7: Logout Session

#### **Actions**
<img width="1920" height="949" alt="Edit Products" src="https://github.com/user-attachments/assets/f3b1a0c7-79d0-491b-8a1b-e5a217855c5a" />
Figure 8: Edit Product Details

<img width="1920" height="964" alt="Delete Product " src="https://github.com/user-attachments/assets/f18a089d-0574-4e81-80ac-01dcd692e02f" />
Figure 9: Delete Product 

<img width="1920" height="940" alt="Restock" src="https://github.com/user-attachments/assets/2709ca16-4697-461c-8f5b-a7b6034c3a9b" />
Figure 10: Restock Products

### **Invoice List View**
<img width="1920" height="949" alt="Invoices" src="https://github.com/user-attachments/assets/117ab8d5-65c4-4fd2-982a-4c9e8c2993fe" />
Figure 11: Invoice List 

<img width="1920" height="944" alt="Invoice" src="https://github.com/user-attachments/assets/f274b5d2-9cfb-4cb4-a855-556b88370dd5" />
Figure 12: View the individual incoice that are generated

### **Low Stock View and Alerts**
<img width="1920" height="950" alt="Low Stock " src="https://github.com/user-attachments/assets/7d0191a2-0147-45fc-9ddf-ffaa88b57150" />
Figure 13: Low Stock List 

<img width="1920" height="940" alt="Restock" src="https://github.com/user-attachments/assets/aa94681f-1a35-40e1-baff-4893baf71e44" />
Figure 14: Restock the products that have low stock

<img width="1920" height="942" alt="Restock Mail " src="https://github.com/user-attachments/assets/eec35e82-bada-44a0-8253-41332521bd06" />
Figure 15: Restock Mail when quantity is zero.


### **Login and Reistration **
<img width="1920" height="346" alt="Login" src="https://github.com/user-attachments/assets/9688c885-e28e-4c9f-8417-cee57e7299ca" />
Figure 16: Login 

<img width="1920" height="482" alt="Registration" src="https://github.com/user-attachments/assets/2a6aaee3-57e4-4cfc-94f1-58e0749e64f5" />
Figure 17: Register if new user 

<img width="1920" height="643" alt="Registration Mail" src="https://github.com/user-attachments/assets/5c7336d3-e5b6-498c-81d3-67a0c80625d2" />
Figure 18: Registration Successful mail with username and password 







