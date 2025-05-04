<?php
// includes/functions.php

// =====================
// Database/Book Queries
// =====================
function getBookById($conn, $book_id) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
    return $book;
}

function getAllBooks($conn, $filters = []) {
    $query = "SELECT * FROM books";
    $params = [];
    $types = '';
    $where = [];

    if (!empty($filters['category']) && $filters['category'] !== 'all') {
        $where[] = "category = ?";
        $params[] = $filters['category'];
        $types .= 's';
    }
    if ($where) {
        $query .= " WHERE " . implode(' AND ', $where);
    }
    if (!empty($filters['sort'])) {
        switch ($filters['sort']) {
            case 'price_asc':
                $query .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY price DESC";
                break;
            case 'featured':
            default:
                $query .= " ORDER BY featured DESC, book_id DESC";
                break;
        }
    } else {
        $query .= " ORDER BY featured DESC, book_id DESC";
    }
    $stmt = $conn->prepare($query);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    $stmt->close();
    return $books;
}

function getBookStock($conn, $book_id) {
    $stmt = $conn->prepare("SELECT stock FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ? (int)$row['stock'] : 0;
}

function getCategories($conn) {
    $categories = [];
    $result = $conn->query("SELECT DISTINCT category FROM books ORDER BY category");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['category'];
        }
    }
    return $categories;
}

function getFilteredBooks($conn, $params = [])
{
    $search = isset($params['search']) ? $conn->real_escape_string(trim($params['search'])) : '';
    $category = isset($params['category']) ? $conn->real_escape_string($params['category']) : '';
    $sort = isset($params['sort']) ? $params['sort'] : '';
    $query = "SELECT * FROM books";
    $conditions = [];
    if (!empty($search)) {
        $conditions[] = "title LIKE '%$search%'";
    }
    if (!empty($category) && $category !== 'all') {
        $conditions[] = "category = '$category'";
    }
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }
    switch ($sort) {
        case 'price_asc':
            $query .= " ORDER BY price ASC";
            break;
        case 'price_desc':
            $query .= " ORDER BY price DESC";
            break;
        case 'featured':
        default:
            $query .= " ORDER BY featured DESC, book_id DESC";
            break;
    }
    return $conn->query($query);
}

// =====================
// Cart/Session Logic
// =====================
function addToCart($book, $quantity) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $book_id = $book['book_id'];
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$book_id] = [
            'book_id' => $book['book_id'],
            'title' => $book['title'],
            'price' => $book['price'],
            'quantity' => $quantity,
            'image' => $book['image']
        ];
    }
}

function incrementCartItem($book_id) {
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity']++;
    }
}

function decrementCartItem($book_id) {
    if (isset($_SESSION['cart'][$book_id]) && $_SESSION['cart'][$book_id]['quantity'] > 1) {
        $_SESSION['cart'][$book_id]['quantity']--;
    }
}

function removeCartItem($book_id) {
    if (isset($_SESSION['cart'][$book_id])) {
        unset($_SESSION['cart'][$book_id]);
    }
}

function updateQuantitiy($quantities) {
    foreach ($quantities as $book_id => $quantity) {
        $quantity = filter_var($quantity, FILTER_VALIDATE_INT);
        if (!$quantity || $quantity < 1) {
            $quantity = 1;
        }
        if (isset($_SESSION['cart'][$book_id])) {
            $_SESSION['cart'][$book_id]['quantity'] = $quantity;
        }
    }
}

function addToCartPost($conn) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $book_id = filter_var($_POST['book_id'], FILTER_VALIDATE_INT);
        $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
        if (!$quantity || $quantity < 1) $quantity = 1;
        $book = getBookById($conn, $book_id);
        if ($book && $book['stock'] > 0) {
            addToCart($book, $quantity);
            return true;
        }
    }
    return false;
}

// =====================
// Order Utils
// =====================
function calculateCartTotals($conn) {
    $totalPrice = 0;
    $bookDetails = [];
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            if (!isset($item['book_id']) || !isset($item['quantity'])) continue;
            $book = getBookById($conn, $item['book_id']);
            if ($book) {
                $bookPrice = $book['price'];
                $bookTotal = $bookPrice * $item['quantity'];
                $totalPrice += $bookTotal;
                $bookDetails[] = [
                    'title' => $book['title'],
                    'price' => $bookPrice,
                    'quantity' => $item['quantity'],
                    'total' => $bookTotal,
                ];
            }
        }
    }
    return ['totalPrice' => $totalPrice, 'bookDetails' => $bookDetails];
}

function createOrder($conn, $cart, $finalTotal) {
    $orderId = null;
    $errorMessage = '';
    $sql = "INSERT INTO orders (customer_id, total_price, order_date) VALUES (NULL, $finalTotal, NOW())";
    if (mysqli_query($conn, $sql)) {
        $orderId = mysqli_insert_id($conn);
        foreach ($cart as $item) {
            if (!isset($item['book_id']) || !isset($item['quantity'])) continue;
            $book = getBookById($conn, $item['book_id']);
            if ($book) {
                $quantity = $item['quantity'];
                $bookPrice = $book['price'];
                $currentStock = $book['stock'];
                if ($currentStock >= $quantity) {
                    $newStock = $currentStock - $quantity;
                    $updateStockSql = "UPDATE books SET stock = $newStock WHERE book_id = {$book['book_id']}";
                    mysqli_query($conn, $updateStockSql);
                    $orderItemSql = "INSERT INTO order_items (order_id, book_id, quantity, price) VALUES ($orderId, {$book['book_id']}, $quantity, $bookPrice)";
                    mysqli_query($conn, $orderItemSql);
                } else {
                    $errorMessage = "Not enough stock for book ID: {$book['book_id']}";
                }
            }
        }
    } else {
        $errorMessage = "Error creating order: " . mysqli_error($conn);
    }
    return [$orderId, $errorMessage];
}

// =====================
// Product Utils
// =====================
function formatPrice($price) {
    return number_format($price, 2);
}

function isInStock($book) {
    return isset($book['stock']) && $book['stock'] > 0;
}

function getDeliveryDate() {
    $minDelivery = date('M j', strtotime('+3 days'));
    $maxDelivery = date('M j', strtotime('+5 days'));
    return [$minDelivery, $maxDelivery];
}

// =====================
// Session Helper
// =====================
function requireAdmin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin_username'])) {
        header('Location: login.php');
        exit();
    }
}

// =====================
// Messaging Utils
// =====================
function showError($message) {
    echo '<div class="error-message"><i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($message) . '</div>';
}

function showSuccess($message) {
    echo '<div class="message-box success"><i class="fas fa-check-circle"></i> ' . htmlspecialchars($message) . '</div>';
}

function showInfo($message) {
    echo '<div class="info-message"><i class="fas fa-info-circle"></i> ' . htmlspecialchars($message) . '</div>';
}

// =====================
// UI Components
// =====================
function showBookCard($book, $basePath = '../') {
    ?>
    <div class="book-card">
        <a href="<?php echo $basePath; ?>pages/product-details.php?id=<?php echo $book['book_id']; ?>" class="card-link">
            <div class="card-img-container">
                <?php if (!empty($book['featured'])): ?>
                    <div class="featured-tag">
                        <i class="fas fa-star"></i> Featured
                    </div>
                <?php endif; ?>
                <img src="<?php echo $basePath; ?>assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="card-img">
                <div class="view-details">View Details</div>
            </div>
        </a>
        <div class="card-content">
            <h3 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h3>
            <span class="card-category"><?php echo htmlspecialchars($book['category']); ?></span>
            <div class="card-price">
                <span class="symbol">&#xea;</span><?php echo formatPrice($book['price']); ?>
            </div>
            <div class="card-stock <?php echo $book['stock'] == 0 ? 'stock-out' : ($book['stock'] <= 5 ? 'stock-low' : 'stock-in'); ?>">
                <?php if ($book['stock'] == 0): ?>
                    <i class="fas fa-times-circle"></i>
                    <span>Out of Stock</span>
                <?php elseif ($book['stock'] <= 5): ?>
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Only <?php echo htmlspecialchars($book['stock']); ?> left in stock</span>
                <?php else: ?>
                    <i class="fas fa-check-circle"></i>
                    <span>In Stock</span>
                <?php endif; ?>
            </div>
            <form method="post" style="margin-top:10px;">
                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" name="add_to_cart" class="add-to-cart-btn" <?php if ($book['stock'] == 0) echo 'disabled style="opacity:0.5;cursor:not-allowed;"'; ?>>
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </form>
        </div>
    </div>
    <?php
}

function showBookRow($book) {
    $stock = (int)$book['stock'];
    $stockClass = $stock < 5 ? 'low-stock' : 'in-stock';
    $category = htmlspecialchars($book['category']);
    $categoryBadge = "<span style='background:#e3fcec;color:#38a169;padding:4px 12px;border-radius:12px;font-size:0.95em;font-weight:500;'>$category</span>";
    if (!$category) $categoryBadge = "<span style='background:#fbe9e7;color:#e57373;padding:4px 12px;border-radius:12px;font-size:0.95em;font-weight:500;'>Uncategorized</span>";
    ?>
    <tr>
        <td style="display:flex;align-items:center;justify-content:flex-start;gap:18px;">
            <div style="background:#fff;border-radius:10px;box-shadow:0 2px 8px #e3e3e3;display:flex;align-items:center;justify-content:center;width:100px;height:100px;min-width:100px;min-height:100px;max-width:100px;max-height:100px;overflow:hidden;">
                <img src="../assets/images/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" style="width:100%;height:100%;object-fit:contain;display:block;background:#f8f9fa;">
            </div>
            <span style="font-weight:600;color:#222;font-size:1.08rem;margin-left:10px;">
                <?php echo htmlspecialchars($book['title']); ?>
            </span>
        </td>
        <td class="price" style="color:#1976d2;font-weight:700;"><span class="symbol">&#xea;</span><?php echo htmlspecialchars($book['price']); ?></td>
        <td class="stock"><span class="<?php echo $stockClass; ?>" style="padding:4px 10px;border-radius:10px;font-weight:600;font-size:0.97em;"><?php echo $stock; ?></span></td>
        <td class="category"><?php echo $categoryBadge; ?></td>
        <td style="min-width:90px;">
            <div style="display:flex;justify-content:center;align-items:center;gap:8px;width:100%;height:100%;">
                <a href="edit-book.php?id=<?php echo $book['book_id']; ?>" class="edit-btn action-btn" title="Edit"><i class="fas fa-edit"></i></a>
                <form method="post" action="delete-book.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $book['book_id']; ?>">
                    <button type="submit" class="delete-btn action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this book?');"><i class="fas fa-trash-alt"></i></button>
                </form>
            </div>
        </td>
    </tr>
    <?php
}

// =====================
// Utility Functions
// =====================
function getBasePath() {
    $currentPath = $_SERVER['PHP_SELF'];
    if (strpos($currentPath, '/pages/') !== false || strpos($currentPath, '/admin/') !== false) {
        return '../';
    } else if (strpos($currentPath, '/includes/') !== false) {
        return '../';
    }
    return '';
}

// =====================
// File Upload Utils
// =====================
function ImageUpload($fileInput, &$errorMessage = null) {
    $target_dir = __DIR__ . '/../assets/images/';
    $image_name = basename($fileInput['name']);
    $target_file = $target_dir . $image_name;
    $allowed_types = ['jpg', 'jpeg', 'png'];
    $file_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_types)) {
        $errorMessage = 'Only JPG, JPEG, and PNG files are allowed.';
        return false;
    }
    if (!move_uploaded_file($fileInput['tmp_name'], $target_file)) {
        $errorMessage = 'Failed to upload image. Check folder permissions.';
        return false;
    }
    return $image_name;
}
