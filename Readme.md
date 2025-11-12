# 🛍️ SafalSales — Full-Stack E-Commerce Web Application

**SafalSales** is a robust and production-ready e-commerce web application developed for **Safal Sales Pvt. Ltd.**  
The platform offers a complete solution for product management, order processing, and billing operations, backed by a secure **MySQL/MariaDB** database.  
It is deployed on **AWS Lightsail** using the **Bitnami LAMP Stack**, configured with **Let’s Encrypt SSL** for HTTPS security and optimized for performance, scalability, and reliability.

---

## 🚀 Core Features

- **Product Management:** Add, update, and manage products with automated image upload functionality.  
- **Billing System:** Generate invoices dynamically with print and preview options.  
- **Database Integration:** Real-time data storage and retrieval using MySQL/MariaDB.  
- **Secure File Handling:** File upload directory configured with proper permissions.  
- **HTTPS Encryption:** Implemented via Let’s Encrypt (`bncert-tool`) for safe and encrypted communication.  
- **PWA Support:** Progressive Web App functionality allows installation on Android devices.  
- **SEO Ready:** Configured with `robots.txt` and `sitemap.xml` for Google indexing.  
- **Admin Interface:** Simple yet efficient dashboard for product and billing control.  

---

## ⚙️ Technology Stack

| Layer | Technology |
|:------|:------------|
| **Frontend** | HTML5, CSS3, JavaScript |
| **Backend** | PHP 8 (Bitnami LAMP Stack) |
| **Database** | MariaDB / MySQL |
| **Hosting** | AWS Lightsail |
| **Security** | HTTPS via Let’s Encrypt SSL |
| **Version Control** | Git & GitHub |

---

## 🧭 Deployment Guide

1. **Upload Files**  
   Place your project files in the following directory:  
   ```bash
   /opt/bitnami/apache/htdocs/
