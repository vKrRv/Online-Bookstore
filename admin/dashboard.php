<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Bookstore</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>

    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>
<h1 class="title">Admin Dashboard</h1>


<section class="admin-section">
    <h3>Manage Products</h3>
    <form class="admin-form">
        <input type="text" placeholder="Book Title" required>
        <input type="text" placeholder="Author" required>
        <input type="number" placeholder="Price (e.g., 49.99)" required>
        <textarea placeholder="Description" required></textarea>
        <button type="submit">Add Book</button>
    </form>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Title</th><th>Author</th><th>Price</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Quantum Computing</td><td>John Doe</td><td><span class="symbol">&#xea;</span> 49.99</td><td><span class="delete-btn">Delete</span></td></tr>
            <tr><td>Web Dev 101</td><td>Jane Smith</td><td><span class="symbol">&#xea;</span> 29.99</td><td><span class="delete-btn">Delete</span></td></tr>
        </tbody>
    </table>
</section>

<section class="admin-section">
    <h3>View Orders</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order ID</th><th>Customer</th><th>Total</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>#001</td><td>Ali Ahmed</td><td><span class="symbol">&#xea;</span> 63.20</td><td>Confirmed</td></tr>
            <tr><td>#002</td><td>Fatimah Noor</td><td><span class="symbol">&#xea;</span> 89.50</td><td>Pending</td></tr>
        </tbody>
    </table>
</section>

<section class="admin-section">
    <h3>User Management</h3>
    <form class="admin-form">
        <input type="text" placeholder="User Full Name" required>
        <input type="email" placeholder="Email Address" required>
        <button type="submit">Add User</button>
    </form>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Ali Ahmed</td><td>ali@example.com</td><td><span class="delete-btn">Delete</span></td></tr>
            <tr><td>Fatimah Noor</td><td>fatimah@example.com</td><td><span class="delete-btn">Delete</span></td></tr>
        </tbody>
    </table>
</section>

<footer>
    <p>&copy; 2025 Online Bookstore. Admin Panel.</p>
</footer>
</body>
</html>
