<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Book Haven</title>
    <link href="../css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/db.php'; ?>

    <?php
    if (!isset($_SESSION['admin_username'])) { // redirect if not logged in
        header('Location: login.php');
        exit();
    }

    $addBookMessage = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'], $_POST['description'], $_POST['price'], $_POST['stock'], $_POST['category'])) { //submit form
        // sanitize input
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $price = trim($_POST['price']);
        $stock = trim($_POST['stock']);
        $category = trim($_POST['category']);
        $image_name = '';

        // check if image is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = __DIR__ . '/../assets/images/'; // save here
            $image_name = basename($_FILES['image']['name']); // get name
            $target_file = $target_dir . $image_name; // target path
            $allowed_types = ['jpg', 'jpeg', 'png'];
            // check file extension
            $file_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // check if its an image
            if (in_array($file_ext, $allowed_types)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    // success
                } else {
                    // failure
                    $addBookMessage = 'Failed to upload image. Check folder permissions.';
                }
            } else {
                // invalid type
                $addBookMessage = 'Only JPG, JPEG, and PNG files are allowed.';
            }
        } else {
            // no image uploaded
            $addBookMessage = 'Please upload an image.';
        }
        // validate input
        if ($title && $description && $image_name && is_numeric($price) && ctype_digit($stock) && $category && empty($addBookMessage)) {
            // prepare and bind
            $stmt = $conn->prepare("INSERT INTO books (title, description, image, price, stock, category) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdis", $title, $description, $image_name, $price, $stock, $category);
            // execute
            if ($stmt->execute()) { // success
                $addBookMessage = "<span style='color:green;'>Book added successfully!</span>";
            } else { //failure
                $addBookMessage = "<span style='color:red;'>Failed to add book.</span>";
            }
        } else if (empty($addBookMessage)) { // missing fields
            $addBookMessage = "<span style='color:red;'>Please fill in all fields correctly.</span>";
        }
    }
    ?>

    <h1 class="title">Admin Dashboard</h1>

    <section class="admin-section">
        <div class="admin-cards-container">
            <div class="admin-card" style="background:#fafdff;box-shadow:0 6px 24px rgba(52,152,219,0.07);border-radius:18px;padding:32px 24px 32px 24px;min-width:340px;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                    <h3 style="margin:0;color:#3498db;font-size:1.3rem;letter-spacing:0.5px;">Add New Book</h3>
                </div>
                <p style="margin-bottom:18px;color:#555;max-width:600px;">Fill out the form below to add a new book to your store inventory.</p>
                <?php if ($addBookMessage) echo "<p>$addBookMessage</p>"; ?>
                <form class="admin-form" method="POST" action="dashboard.php" enctype="multipart/form-data" style="background:#fff;padding:22px 20px 14px 20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.06);display:flex;flex-direction:column;gap:16px;">
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <label for="title" style="font-weight:600;color:#2980b9;">Book Title</label>
                        <input type="text" id="title" name="title" placeholder="Book Title" required style="padding:12px 14px;font-size:1.05rem;border-radius:7px;border:1px solid #e1e1e1;">
                    </div>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <label for="description" style="font-weight:600;color:#2980b9;">Description</label>
                        <textarea id="description" name="description" placeholder="Description" required style="padding:12px 14px;font-size:1.05rem;border-radius:7px;border:1px solid #e1e1e1;resize:none;min-height:90px;"></textarea>
                    </div>
                    <div style="display:flex;gap:16px;">
                        <div style="flex:1;display:flex;flex-direction:column;gap:6px;">
                            <label for="price" style="font-weight:600;color:#2980b9;">Price</label>
                            <input type="number" id="price" name="price" placeholder="Price" min="0" step="0.01" required style="padding:12px 14px;font-size:1.05rem;border-radius:7px;border:1px solid #e1e1e1;">
                        </div>
                        <div style="flex:1;display:flex;flex-direction:column;gap:6px;">
                            <label for="stock" style="font-weight:600;color:#2980b9;">Stock Quantity</label>
                            <input type="number" id="stock" name="stock" placeholder="Stock Quantity" min="0" step="1" required style="padding:12px 14px;font-size:1.05rem;border-radius:7px;border:1px solid #e1e1e1;">
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <label for="category" style="font-weight:600;color:#2980b9;">Category</label>
                        <input type="text" id="category" name="category" placeholder="Category" required style="padding:12px 14px;font-size:1.05rem;border-radius:7px;border:1px solid #e1e1e1;">
                    </div>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <label for="image" style="font-weight:600;color:#2980b9;">Book Image</label>
                        <input type="file" id="image" name="image" accept="image/*" class="file-input" style="padding:14px 0 14px 0;background:#f8f9fa;border:1.5px dashed #3498db;border-radius:10px;transition:all 0.2s;cursor:pointer;font-size:1.05rem;">
                    </div>
                    <button type="submit" class="search-button" style="margin-top:8px;width:90%;max-width:400px;align-self:center;">Add Book</button>
                </form>
            </div>
            <div class="admin-card" style="background:#fafdff;box-shadow:0 6px 24px rgba(52,152,219,0.07);border-radius:18px;padding:32px 24px 32px 24px;min-width:340px;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                    <h3 style="margin:0;color:#3498db;font-size:1.3rem;letter-spacing:0.5px;">View Orders</h3>
                    <span style="background:#e3f2fd;color:#1976d2;padding:6px 18px;border-radius:16px;font-size:0.98rem;font-weight:600;">Total: <?php echo $conn->query('SELECT COUNT(*) FROM orders')->fetch_row()[0]; ?></span>
                </div>
                <p style="margin-bottom:18px;color:#555;max-width:600px;">See the latest orders placed in your store. Track customers and order totals easily.</p>
                <div style="overflow-x:auto; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.04); background:#fff;">
                    <table class="admin-table" style="min-width:420px;">
                        <thead style="background:linear-gradient(90deg,#e3f2fd 60%,#f8fafc);">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // fetch latest orders
                            $orders = $conn->query("SELECT o.order_id, o.order_date, o.total_price, c.name FROM orders o LEFT JOIN customers c ON o.customer_id = c.customer_id ORDER BY o.order_date DESC LIMIT 10");
                            // check if orders exist
                            if ($orders && $orders->num_rows > 0) {
                                $rowIndex = 0;
                                // loop and fetch
                                while ($order = $orders->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td style='font-weight:600;color:#222;'>" . htmlspecialchars($order['order_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($order['name'] ?? 'Guest') . "</td>";
                                    echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                                    echo "<td style='width:120px;'><span class='symbol'>&#xea;</span>" . htmlspecialchars($order['total_price']) . "</td>";
                                    echo "</tr>";
                                    $rowIndex++;
                                }
                            } else { // not found
                                echo '<tr><td colspan="4">No orders found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="admin-table-container" style="background: #fafdff; box-shadow: 0 6px 24px rgba(52,152,219,0.07); border-radius: 18px; padding: 32px 24px 32px 24px;">
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:10px;">
                <h3 style="margin:0;color:#2980b9;font-size:1.6rem;letter-spacing:0.5px;">Book Inventory</h3>
                <span style="background:#e3f2fd;color:#1976d2;padding:6px 18px;border-radius:16px;font-size:0.98rem;font-weight:600;">Total: <?php echo $conn->query('SELECT COUNT(*) FROM books')->fetch_row()[0]; ?></span>
            </div>
            <p style="margin-bottom:18px;color:#555;max-width:600px;">Manage all books in your store. Use the search to quickly find a book by title. Edit or delete books as needed.</p>
            <div style="width:100%;display:flex;justify-content:center;align-items:center;margin-bottom:24px;background:#fff;padding:18px 0 18px 0;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                <form method="GET" action="" class="admin-form" style="display: flex; align-items: center; background: none; border-radius: 8px; box-shadow: none; padding: 0; margin: 0;">
                    <input type="text" name="search" placeholder="Search by Title..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" style="width: 320px; min-width: 180px; text-align: left; margin-bottom: 0;">
                    <button type="submit" class="search-button" style="margin-left: 12px;">Search</button>
                </form>
            </div>
            <div style="overflow-x:auto; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.04); background:#fff;">
                <table class="admin-table" id="productTable" style="min-width:900px;">
                    <thead style="background:linear-gradient(90deg,#e3f2fd 60%,#f8fafc);">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // get search term
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $query = "SELECT * FROM books";
                        if (!empty($search)) {
                            $search = $conn->real_escape_string($search);
                            $query .= " WHERE title LIKE '%$search%'"; //search by title
                        }
                        $books = $conn->query($query);
                        $rowIndex = 0;
                        while ($row = $books->fetch_assoc()) { /// loop and fetch
                            $stock = (int)$row['stock'];
                            $stockClass = $stock < 5 ? 'low-stock' : 'in-stock';
                            $category = htmlspecialchars($row['category']);
                            $categoryBadge = "<span style='background:#e3fcec;color:#38a169;padding:4px 12px;border-radius:12px;font-size:0.95em;font-weight:500;'>$category</span>";
                            if (!$category) $categoryBadge = "<span style='background:#fbe9e7;color:#e57373;padding:4px 12px;border-radius:12px;font-size:0.95em;font-weight:500;'>Uncategorized</span>";
                            echo "<tr>
                            <td style='display:flex;align-items:center;justify-content:flex-start;gap:18px;'>
                                <div style='background:#fff;border-radius:10px;box-shadow:0 2px 8px #e3e3e3;display:flex;align-items:center;justify-content:center;width:100px;height:100px;min-width:100px;min-height:100px;max-width:100px;max-height:100px;overflow:hidden;'>
                                    <img src='../assets/images/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['title']) . "' style='width:100%;height:100%;object-fit:contain;display:block;background:#f8f9fa;'>
                                </div>
                                <span style='font-weight:600;color:#222;font-size:1.08rem;margin-left:10px;'>" . htmlspecialchars($row['title']) . "</span>
                            </td>
                            <td class='price' style='color:#1976d2;font-weight:700;'><span class='symbol'>&#xea;</span>" . htmlspecialchars($row['price']) . "</td>
                            <td class='stock'><span class='$stockClass' style='padding:4px 10px;border-radius:10px;font-weight:600;font-size:0.97em;'>$stock</span></td>
                            <td class='category'>$categoryBadge</td>
                            <td style='min-width:90px;'>
                                <div style='display:flex;justify-content:center;align-items:center;gap:8px;width:100%;height:100%;'>
                                    <a href='edit-book.php?id={$row['book_id']}' class='edit-btn action-btn' title='Edit'><i class='fas fa-edit'></i></a>
                                    <a href='delete-book.php?id={$row['book_id']}' class='delete-btn action-btn' title='Delete' onclick=\"return confirm('Are you sure you want to delete this book?');\"><i class='fas fa-trash-alt'></i></a>
                                </div>
                            </td>
                        </tr>";
                            $rowIndex++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>
</body>

</html>