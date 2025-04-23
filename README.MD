# Online Bookstore

This is an online bookstore web application  built using HTML, CSS, JavaScript, and PHP. This project enables users to browse, search, and purchase books through a simple and intuitive interface.

## Tech Stack

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Server: Apache (via XAMPP)

## File Structure
```python
online-bookstore/
├── admin/                     # Admin-only pages
│   ├── login.php              
│   ├── add-book.php           
│   ├── edit-book.php          
│   ├── delete-book.php        
│   └── dashboard.php          
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
│   ├── header.php             # Shared header HTML
│   └── footer.php             # Shared footer HTML
│
├── js/                        # JavaScript files
│   └── validation.js                  
│
├── pages/                     # Main user-facing pages
│   ├── product-details.php    # Product detail page         
│   ├── cart.php               # Shopping cart
│   ├── checkout.php           # Checkout and order review
│   ├── bill.php               # Order receipt/bill
│   ├── contact.php            # Contact page + map
│   ├── products.php           # Products list page
│   ├── signup.php             # Signup page for users
│   └── ...
│             
├── bookstore.sql              # Database schema + sample data
├── index.php                  # Home page with product listings
└── README.MD                  
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
Edit `config.php`:
```php
return [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'db'   => 'bookstore'
];
```

### 5. Access the Website
Visit:
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
