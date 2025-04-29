<p align="center">
  <img src="src/assets/images/court-kart-logo-dark.png" alt="CourtKart Logo" width="200"/>
</p>

## Project Summary

**CourtKart** is a pure PHP and MySQL-based e-commerce web application tailored for basketball enthusiasts. It offers a clean shopping experience for buying basketball shoes, jerseys, balls, accessories, and more—complete with admin controls, search filters, cart system, and backend database logic including procedures and triggers.

---

## Features

### User Features

- View all basketball-related products
- See item details with image, price, and description
- Search and filter products
- Register, login, and logout
- Add/remove/view items in cart
- Finalize orders

### Admin Features

- Access a dedicated admin panel
- Add new products to the shop

### Database Logic

- Use of stored procedures:
  - Show order details and total price
  - Finalize order & empty cart
  - View order history
- Use of triggers:
  - Decrease stock after order
  - Prevent order if stock is insufficient
  - Restore stock when order is canceled
  - Log canceled orders into a history table

---

## Screenshots

_Screenshots will be added soon..._

---

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript (vanilla)
- **Backend**: PHP (no frameworks)
- **Database**: MySQL
- **Hosting**: Localhost via XAMPP, MAMP, or LAMP
- **Sessions & Cookies**: For cart and user auth

---

## Folder Structure

```plaintext
court-kart-store/
├── index.php                  ← homepage
├── shop/
│   ├── index.php              ← list products
│   ├── item.php               ← product details
│   ├── search.php             ← filter logic
├── cart/
│   ├── view.php               ← show cart
│   ├── add.php                ← add to cart
│   └── remove.php             ← remove from cart
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── admin/
│   ├── index.php              ← admin dashboard
│   └── add_item.php           ← add new product
├── assets/
│   ├── styles.css             ← all styles
│   └── scripts.js             ← all JS
├── includes/
│   ├── db.php                 ← database connection
│   ├── header.php             ← navbar, head
│   └── footer.php             ← footer content
├── sql/
│   ├── schema.sql             ← table creation
│   ├── procedures.sql         ← procedures
│   └── triggers.sql           ← triggers
```

---

## Database Schema (ERD)

```plantuml
@startuml CourtKartDB
' General background
skinparam backgroundColor #0d1117
skinparam defaultTextColor #c9d1d9

' Class table styles
skinparam classBorderColor #30363d
skinparam classBackgroundColor #161b22
skinparam classAttributeFontColor #c9d1d9
skinparam classHeaderBackgroundColor #21262d
skinparam classHeaderFontColor #58a6ff
skinparam classHeaderFontStyle bold

' Arrows
skinparam arrowColor #30363d
skinparam arrowFontColor #c9d1d9

' Arrow label font
skinparam ArrowFontColor #c9d1d9

entity "users" as users {
  *id : INT <<PK>>
  --
  name : VARCHAR(100)
  email : VARCHAR(100) <<UNIQUE>>
  password : VARCHAR(255)
  role : ENUM('user', 'admin')
  created_at : TIMESTAMP
}

entity "products" as products {
  *id : INT <<PK>>
  --
  name : VARCHAR(100)
  description : TEXT
  price : DECIMAL(10,2)
  stock : INT
  image_url : VARCHAR(255)
  category : VARCHAR(50)
  created_at : TIMESTAMP
}

entity "cart_items" as cart_items {
  *id : INT <<PK>>
  --
  user_id : INT <<FK>>
  product_id : INT <<FK>>
  quantity : INT
}

entity "orders" as orders {
  *id : INT <<PK>>
  --
  user_id : INT <<FK>>
  total_price : DECIMAL(10,2)
  status : ENUM('pending', 'confirmed', 'cancelled')
  created_at : TIMESTAMP
}

entity "order_items" as order_items {
  *id : INT <<PK>>
  --
  order_id : INT <<FK>>
  product_id : INT <<FK>>
  quantity : INT
  price : DECIMAL(10,2)
}

entity "canceled_orders" as canceled_orders {
  *id : INT <<PK>>
  --
  order_id : INT <<FK>>
  reason : TEXT
  canceled_at : TIMESTAMP
}

entity "logs" as logs {
  *id : INT <<PK>>
  --
  action : VARCHAR(100)
  user_id : INT <<FK>>
  order_id : INT <<FK>><<nullable>>
  message : TEXT
  created_at : TIMESTAMP
}

' Relationships
users ||--o{ cart_items : has
users ||--o{ orders : places
users ||--o{ logs : generates

products ||--o{ cart_items : includes
products ||--o{ order_items : sold_in

orders ||--o{ order_items : contains
orders ||--|| canceled_orders : may_be
orders ||--o{ logs : associated_with

@enduml
```

## Author

Made by [Adel2411](https://github.com/Adel2411)  
For educational purposes — built with ❤️ for basketball and clean code.

---

## License

This project is open source and available under the [MIT License](LICENSE).

---

> **Disclaimer**: This project is built without any external libraries or frameworks, intended for local development and academic demonstration only.
