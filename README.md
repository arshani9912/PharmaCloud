# 📑 PharmaCloud – Pharmacy Stock Management System  

- Author / Student: Arshani Muthumali
- Supervisor: Ms.M.A.L.Kalyani
- Client: Owner of Sahana Pharmacy, Karapitiya - Mr.Sarath Welivita
- Date: 22-08-2025

---

## 1. Introduction  

**PharmaCloud** Pharmacy Stock Management System is a web-based application designed to help pharmacies efficiently manage their inventory, track medicine stock levels, and streamline sales and purchase processes. The system allows Pharmacist to add, update, and monitor medicines, record transactions, and generate basic reports. It ensures accurate stock management, reduces errors, and helps pharmacy owners make informed decisions. Built using PHP, MySQL, and Bootstrap, the system supports secure user authentication and role-based access for Pharmacist, Customer and administrators.

---

## 2. Objectives  

- Develop a secure, user-friendly system for **medicine inventory management**.  
- Track sales, purchases, and stock levels efficiently.  
- Generate reports for stock, sales, low stock alerts and expiry monitoring.  
- Provide **role-based access** for Admins, Pharmacists and Customers.  
- Build the system using **PHP, MySQL, and Bootstrap**.  

---

## 3. Problem Definition  

Manual inventory management in pharmacies often leads to:  

- Stock mismanagement and shortages  
- Difficulty in tracking sales and purchases  
- Inefficient reporting and decision-making  
- Security risks with manual records  

PharmaCloud automates inventory management, streamlines operations, and provides **real-time reporting**.  

---
### Use Case Diagram

<img src="https://github.com/arshani9912/PharmaCloud/blob/5f367cdb40b9f5707b3dc93f39bd8358b0974aae/Use%20Case%20Diagram.png" alt="Use Case Diagram" width="500">

### ER Diagram

<img src="https://github.com/arshani9912/PharmaCloud/blob/f48b1f93f5d18be756c4ac9e3c3a57c8876a9a0b/ER_Diagram.png" alt="ER Diagram" width="500">

## 4. Scope of the Project  

The system will include:  

1. **User Management**  
   - Admins: full access to all modules (Create, Update & Deactivate User Accounts)
   - Pharmacist: access limited to sales and reports
   - Customer : can view available medicines in the pharmacy

2. **Medicine Management**  
   - Add, edit, delete medicines  
   - Track stock levels, batch numbers, and expiry dates  

3. **Sales & Purchase Tracking**  
   - Record transactions and purchase orders  
   - Manage supplier details  

4. **Reporting & Analytics**  
   - Stock reports  
   - Sales reports  
   - Low-stock alerts
   - Expiry dates approaching within a configurable number of days.  

5. **User-Friendly Interface**  
   - Responsive design using Bootstrap  
   - Clean dashboard for easy navigation  

---

## 5. Technologies Used  

| Technology | Purpose |  
|------------|---------|  
| PHP        | Backend logic |  
| MySQL      | Database management |  
| Bootstrap  | Frontend design & responsiveness |  
| HTML/CSS/JS| User interface & interactivity |  
| Git/GitHub | Version control |

---

## 6. Features  

| 🔹 Feature | 📝 Description |  
|------------|----------------|  
| **🔒 User Authentication & Roles** | Secure login for Admins and Staff with role-based access |  
| **💊 Medicine Management** | Add, edit, delete medicines; track stock levels and expiry |  
| **💵 Sales & Purchase Tracking** | Record sales, manage purchase orders, suppliers |  
| **📊 Reports & Analytics** | Generate stock and sales reports; monitor low-stock items |  
| **📱 Responsive Design** | Built with Bootstrap for mobile-friendly interface |  
| **⚡ Easy-to-Use Dashboard** | Intuitive UI for fast operations |  

---

## 7. Implementation Plan

### Duration: 8 Weeks

| Week | Tasks                                                |
| ---- | ---------------------------------------------------- |
| 1-2  | Requirements gathering, ER diagram, Use Case diagram |
| 3    | Database design & setup (MySQL)                      |
| 4-5  | Backend development (PHP)                            |
| 6    | Frontend development (Bootstrap, HTML/CSS/JS)        |
| 7    | Integration & testing                                |
| 8    | Deployment, final report, and documentation          |

<img src="https://github.com/arshani9912/PharmaCloud/blob/8ff4a9af6be386c08defbf927c290bd157477885/Gantt%20Chart.png" alt="ER Diagram" width="500">

## 8. Expected Outcomes

- Fully functional PharmaCloud system deployed locally or on server
- Accurate tracking of inventory, sales, and purchases
- Automated reporting and low-stock alerts
- Secure user authentication and role-based access
- User-friendly interface for Admins and Staff

## 9. References

- PHP Official Documentation
- MySQL Documentation
- Bootstrap Documentation
