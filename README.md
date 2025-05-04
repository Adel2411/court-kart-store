<p align="center">
  <img src="public/assets/images/court-kart-logo-dark.png" alt="CourtKart Logo" width="200"/>
</p>

# Court-Kart Store

## Project Summary

**CourtKart** is a full-featured e-commerce web application built with pure PHP and MySQL, specifically designed for basketball enthusiasts. The platform offers a seamless shopping experience for basketball shoes, jerseys, equipment, and accessories—implementing a complete solution with robust user management, product catalog, shopping cart functionality, and comprehensive admin controls.

This project demonstrates advanced PHP concepts including MVC architecture, session management, secure authentication, and complex database operations with stored procedures and triggers.

---

## Features

### Customer Features

- **Product Browsing**: Browse through categorized basketball products
- **Search & Filter**: Find products by name, price range, category, and brand
- **Account Management**: Register, login, view profile, and order history
- **Shopping Cart**: Add/remove items, adjust quantities, view cart totals
- **Checkout Process**: Address entry, payment method selection, order confirmation
- **Order Tracking**: View status and details of past orders

### Admin Features

- **Product Management**: Add, edit, and remove products from inventory
- **Order Administration**: Process orders, update status, and view order details
- **User Management**: View and manage customer accounts
- **Inventory Control**: Monitor stock levels with automatic alerts
- **Analytics Dashboard**: View sales data and popular products

### Database Features

- **Stored Procedures**:
  - `GetOrderDetails`: Retrieve order details with calculated total
  - `FinalizeOrder`: Process order completion and empty cart
  - `GetCustomerOrderHistory`: Display all orders for a customer
  
- **Triggers**:
  - `after_order_confirm`: Decrease product stock on order confirmation
  - `before_order_insert`: Prevent orders exceeding available stock
  - `after_order_cancel`: Restore inventory when orders are canceled
  - `log_canceled_orders`: Record canceled orders in history table

---

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (vanilla)
- **Backend**: PHP 8.0+ (no frameworks)
- **Database**: MySQL 8.0+
- **Session Management**: PHP native sessions
- **Authentication**: Custom-built, secure hash-based system
- **Development Environment**: XAMPP/LAMP/MAMP

---

## Architecture

CourtKart follows a lightweight MVC (Model-View-Controller) architecture:

- **Models**: Encapsulate database logic and business rules
- **Views**: Handle presentation and templating
- **Controllers**: Process requests and coordinate between models and views
- **Services**: Handle complex business logic operations

---

## Enhanced Folder Structure

```plaintext
court-kart-store/
├── public/                    # Web root - publicly accessible files
│   ├── index.php             # Application entry point
│   ├── assets/               # Static assets
│   │   ├── css/              # Stylesheets
│   │   ├── js/               # JavaScript files
│   │   └── images/           # Images and graphics
│   └── .htaccess             # URL rewriting rules
├── src/                       # Application source code
│   ├── Models/               # Data access layer
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Cart.php
│   │   ├── Order.php
│   │   └── Category.php
│   ├── Controllers/          # Request handlers
│   │   ├── ShopController.php
│   │   ├── CartController.php
│   │   ├── AuthController.php
│   │   ├── OrderController.php
│   │   └── AdminController.php
│   ├── Services/             # Business logic
│   │   ├── AuthService.php
│   │   ├── CartService.php
│   │   └── OrderService.php
│   ├── Core/                 # Framework components
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── Session.php
│   │   └── View.php
│   └── Helpers/              # Utility functions
│       ├── Validator.php
│       └── Security.php
├── config/                    # Configuration files
│   ├── app.php
│   └── database.php
├── views/                     # Template files
│   ├── layouts/              # Reusable layouts
│   │   ├── main.php          # Main site layout
│   │   └── admin.php         # Admin panel layout
│   ├── shop/
│   ├── cart/
│   ├── auth/
│   └── admin/
├── sql/                       # Database scripts
│   ├── schema.sql            # Table structure
│   ├── procedures.sql        # Stored procedures
│   ├── triggers.sql          # Database triggers
│   └── seed.sql              # Sample data
└── bootstrap.php              # Application initialization
```

---

## Installation & Setup

1. **Clone the repository**
   ```
   git clone https://github.com/Adel2411/court-kart-store.git
   ```

2. **Configure your web server**
   - Set document root to the `public` directory
   - Ensure PHP has appropriate permissions

3. **Create the database**
   ```
   mysql -u username -p < sql/schema.sql
   mysql -u username -p < sql/procedures.sql
   mysql -u username -p < sql/triggers.sql
   mysql -u username -p < sql/seed.sql
   ```

4. **Configure database connection**
   - Edit `config/database.php` with your credentials

5. **Start your local server**
   - Run your XAMPP/MAMP/LAMP stack

6. **Access the application**
   - Navigate to `http://localhost` in your browser

---

## Database Schema (ERD)

<p align="center">
  <img src="public/assets/images/db-schema.png" alt="CourtKart Database Schema"/>
</p>

---

## Project Requirements

This project fulfills the following academic requirements:

- Main shop page with detailed product listings
- User authentication system
- Shopping cart functionality
- Product search and filtering
- Database integration with stored procedures and triggers
- Session and cookie management
- Admin interface for product management

---

## Author

Made with ❤️ by [Adel2411](https://github.com/Adel2411)  
For educational purposes — crafted for basketball enthusiasts and clean code advocates.

---

## License

This project is open source and available under the [MIT License](LICENSE).

---

> **Disclaimer**: This project is built without external libraries or frameworks, intended for educational demonstration only.
