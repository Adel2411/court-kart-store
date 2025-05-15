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

- **Product Browsing**: Browse through categorized basketball products with grid and list views
- **Advanced Filtering**: Find products by name, price range, category, and sort by various criteria
- **User Account Management**: Register, login, view profile, and track order history
- **Product Details**: View comprehensive product information, images, and specifications
- **Shopping Cart**: Add/remove items, adjust quantities, view cart totals
- **Checkout Process**: Address entry, payment method selection, order confirmation
- **Order Tracking**: View status and details of past orders

### Admin Features

- **Dashboard**: Overview of store performance and key metrics
- **Product Management**: Add, edit, and remove products from inventory
- **Order Administration**: Process orders, update status, and view order details
- **User Management**: View and manage customer accounts
- **Inventory Control**: Monitor stock levels with automatic alerts

### Technical Features

- **Responsive Design**: Mobile-first approach for all device compatibility
- **Form Validation**: Client and server-side validation for data integrity
- **Session Management**: Secure PHP session handling
- **Database Operations**: Stored procedures and triggers for complex operations
- **Security**: Protection against XSS, CSRF, and SQL injection

---

## Technology Stack

- **Frontend**: 
  - HTML5, CSS3 with responsive design
  - Vanilla JavaScript with modular organization
  - Font Awesome icons
  
- **Backend**: 
  - PHP 8.0+ (no frameworks)
  - MVC architecture
  - Custom routing system
  
- **Database**: 
  - MySQL 8.0+
  - Stored procedures
  - Database triggers
  
- **Development Environment**: 
  - XAMPP/LAMP stack

---

## Project Structure

```plaintext
court-kart-store/
├── public/                    # Public accessible files
│   ├── index.php             # Application entry point
│   ├── assets/               # Static assets
│   │   ├── css/              # Stylesheets (main, components, layouts, pages)
│   │   ├── js/               # JavaScript files (main, pages)
│   │   └── images/           # Images and product photos
│   └── .htaccess             # URL rewriting rules
├── src/                       # Application source code
│   ├── Core/                 # Framework core components
│   │   ├── Database.php      # Database connection handler
│   │   ├── Router.php        # URL routing system
│   │   ├── Session.php       # Session management
│   │   └── View.php          # Template rendering
│   ├── Controllers/          # Request handlers
│   │   ├── HomeController.php
│   │   ├── ShopController.php
│   │   ├── CartController.php
│   │   ├── AuthController.php
│   │   ├── OrderController.php
│   │   ├── CheckoutController.php
│   │   ├── AccountController.php
│   │   └── AdminController.php
│   ├── Models/               # Data models
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Cart.php
│   │   └── Order.php
│   └── Helpers/              # Utility functions
│       ├── Validator.php
│       └── Security.php
├── views/                     # Template files
│   ├── layouts/              # Reusable layouts
│   │   ├── main.php          # Main site layout
│   │   ├── admin.php         # Admin panel layout
│   │   └── footer.php        # Footer partial
│   ├── shop/                 # Shop pages
│   │   ├── index.php         # Product listing page
│   │   └── product.php       # Product detail page
│   ├── cart/                 # Shopping cart views
│   │   └── index.php
│   ├── home.php              # Homepage template
│   └── errors/               # Error pages
│       └── 404.php
├── routes/                    # Routing configuration
│   └── web.php               # Web routes definition
├── config/                    # Configuration files
│   ├── app.php
│   └── database.php
└── bootstrap.php              # Application initialization
```

---

## Key Components

### 1. Routing System
The application uses a custom routing system located in `routes/web.php` that maps URLs to controller actions and supports middleware for authentication:

```php
// Public routes
$router->get('/', 'HomeController@index');
$router->get('/shop', 'ShopController@index');
$router->get('/shop/product/{id}', 'ShopController@show');

// Auth protected routes
$router->get('/cart', 'CartController@index', 'auth');
$router->post('/cart/add', 'CartController@add', 'auth');

// Admin routes
$router->get('/admin/products', 'AdminController@products', 'admin');
```

### 2. View Rendering
Templates are managed through the `View` class which supports layouts and partial templates:

```php
// Render a view with layout
echo View::renderWithLayout('shop/product', 'main', [
    'title' => $product['name'].' - Court Kart',
    'product' => $product,
    'page_css' => 'product'
]);
```

### 3. Database Interaction
Models interact with the database and encapsulate data operations:

```php
// Get filtered products with pagination
$products = Product::getFiltered([
    'category' => $_GET['category'] ?? null,
    'search' => $_GET['search'] ?? null,
    'min_price' => $_GET['min_price'] ?? null,
    'max_price' => $_GET['max_price'] ?? null,
    'sort' => $_GET['sort'] ?? 'newest'
], $page, 12);
```

---

## Installation & Setup

1. **Clone the repository**

   ```
   git clone https://github.com/Adel2411/court-kart-store.git
   cd court-kart-store
   ```

2. **Configure your web server**

   - Set document root to the `public` directory
   - Ensure PHP 8.0+ is installed and configured

3. **Create and populate the database**

   ```
   mysql -u username -p < database/schema.sql
   ```

4. **Configure database connection**

   - Edit `config/database.php` with your credentials

5. **Start your local server**

   - Launch XAMPP/MAMP/LAMP
   - Or use PHP's built-in server: `php -S localhost:8000 -t public`

6. **Access the application**
   - Navigate to `http://localhost:8000` in your browser
   - Admin access: Use credentials admin@courtkart.com / admin123

---

## Screenshots

<p align="center">
  <img src="public/assets/images/screenshots/homepage.png" alt="Homepage" width="48%"/>
  <img src="public/assets/images/screenshots/product-listing.png" alt="Product Listing" width="48%"/>
</p>

---

## Database Schema

The database uses a normalized schema with tables for users, products, categories, orders, and cart items. Key relationships include one-to-many between users and orders, and many-to-many between orders and products via order_items.

<p align="center">
  <img src="public/assets/images/db-schema.png" alt="CourtKart Database Schema"/>
</p>

---

## Author

Made with ❤️ by [Adel2411](https://github.com/Adel2411)  
For educational purposes — crafted for basketball enthusiasts and clean code advocates.

---

## License

This project is open source and available under the [MIT License](LICENSE).

---

> **Note**: This project is built without external frameworks or libraries, focusing on core PHP principles and clean code structure.
