<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Bookstore</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include '../includes/header.php'; ?>
<?php include '../includes/db.php'; ?>

<h1 class="title">Admin Dashboard</h1>

<section class="admin-section">
    <h1>Manage Products</h1>
    <br>

    <h3>Search Books</h3>
    <!--Form for searching books -->
    <form class="admin-form" method="GET" action="">
        <input type="text" name="search" placeholder="Search by Title..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit" class="search-button">Search</button>
    </form>
<br>
    <!-- Form for adding new books -->
    <h3>Add New Book</h3>
    <form class="admin-form" id="addProductForm" method="POST" action="add_product.php" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Book Title" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" step="0.01" name="price" placeholder="Price (e.g., 49.99)" required>
        <input type="number" name="stock" placeholder="Stock Quantity" required>
        <input type="text" name="category" placeholder="Category" required>
        <button type="submit" class="search-button">Add Book</button>
    </form>

    <!-- Table to display books -->
    <table class="admin-table" id="productTable">
        <thead>
            <tr>
                <th>Title</th><th>Price</th><th>Stock</th><th>Category</th><th>Image</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $query = "SELECT * FROM books";
            if (!empty($search)) {
                $search = $conn->real_escape_string($search);
                $query .= " WHERE title LIKE '%$search%'";
            }
            $books = $conn->query($query);
            while ($row = $books->fetch_assoc()) {
                echo "<tr data-id='{$row['book_id']}'>
                    <td class='title'>".htmlspecialchars($row['title'])."</td>
                    <td class='price'>".htmlspecialchars($row['price'])."</td>
                    <td class='stock'>".htmlspecialchars($row['stock'])."</td>
                    <td class='category'>".htmlspecialchars($row['category'])."</td>
                    <td><img src='../assets/images/".htmlspecialchars($row['image'])."' width='50'></td>
                    <td>
                        <a href='edit-book.php?id={$row['book_id']}' class='edit-btn'>Edit</a> | 
                        <a href='delete-book.php?id={$row['book_id']}' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this book?');\">Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<footer>
    <p>&copy; 2025 Online Bookstore. Admin Panel.</p>
</footer>


</body>
</html>
