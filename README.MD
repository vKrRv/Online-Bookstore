# Online Bookstore

This is an online bookstore web application  built using HTML, CSS, JavaScript, and PHP. This project enables users to browse, search, and purchase books through a simple and intuitive interface.

## Tech Stack

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Server: Apache (via XAMPP)

## File Structure
```
Online-Bookstore/
├── admin/                     # Admin-only pages
│   ├── dashboard.php          # Admin dashboard
│   ├── delete-book.php        # Delete book page
│   ├── edit-book.php          # Edit book page
│   ├── login.php              # Admin login
│   └── logout.php             # Admin logout
│
├── assets/                    # Static assets
│   ├── cards/                 # Payment cards images
│   │   ├── apple-pay.svg
│   │   ├── mada.svg
│   │   ├── mastercard.svg
│   │   └── visa.svg
│   ├── fonts/                 # Font files
│   │   ├── saudiriyalsymbol.otf
│   │   ├── saudiriyalsymbol.woff
│   │   └── saudiriyalsymbol.woff2
│   └── images/                # Product and site images
│       ├── 1984.jpg
│       ├── atomic_habits.jpg
│       ├── clean_code.jpg
│       ├── logo.png
│       ├── start_with_why.jpg
│       ├── the_hobbit.jpg
│       └── thinking_fast_slow.jpg
│
├── css/                       # Stylesheets
│   └── style.css
│
├── includes/                  # Reusable logic
│   ├── config.php             # Database credentials
│   ├── db.php                 # Database connection logic
│   ├── footer.php             # Shared footer HTML
│   └── header.php             # Shared header HTML
│
├── js/                        # JavaScript files
│   ├── cart.js                # Cart logic
│   └── validation.js          # Form validation
│
├── pages/                     # Main user-facing pages
│   ├── bill.php               # Order receipt/bill
│   ├── cart.php               # Shopping cart
│   ├── checkout.php           # Checkout and order review
│   ├── contact.php            # Contact page + map
│   ├── product-details.php    # Product detail page
│   ├── products.php           # Products list page
│   ├── signup.php             # Signup page for users
│   └── signup_process.php     # Signup form handler
│
├── bookstore.sql              # Database schema + sample data
├── index.php                  # Home page with product listings
└── README.MD                  # Project documentation
```

## Instructions

Follow these steps to set up and run the bookstore on your local machine.

---

### 1. Clone the Repository 

```bash
git clone https://github.com/vKrRv/Online-Bookstore.git
cd Online-Bookstore
```

### 2. Set up the Local Server (XAMPP)
- Install  [XAMPP](https://www.apachefriends.org/)
- Start Apache and MySQL from XAMPP control panel
- Move the project folder to `xampp/htdocs/`

### 3. Import the Database
1. Open phpMyAdmin at `http://localhost/phpmyadmin`
2. Create a new database named `bookstore`
3. Import `bookstore.sql` file

### 4. Configure Database Connection 
Create `config.php` in `includes/` and add the following:
```php
return [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'db'   => 'bookstore' 
];
```

### 5. Access the Website
Visit:v
```arduino
http://localhost/online-bookstore/
```
Admin login:
```text
Username: test
Password: 123
```


## License

This project is for educational purposes only.
