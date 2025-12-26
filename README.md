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
