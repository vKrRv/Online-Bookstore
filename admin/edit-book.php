<?php
session_start();
require_once '../includes/functions.php';
requireAdmin();

require_once '../includes/db.php'; // include db connection

if (isset($_GET['id'])) { // get book id
    $book_id = (int) $_GET['id'];
    $result = $conn->query("SELECT * FROM books WHERE book_id = $book_id"); // fetch details

    if ($result && $row = $result->fetch_assoc()) {
        // book found
    } else {
        echo "Book not found.";
        exit;
    }
} elseif (isset($_POST['update'])) {
    $book_id = (int) $_POST['book_id']; // get book id from form
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $category = $conn->real_escape_string($_POST['category']);

    if (!empty($_FILES['image']['name'])) { // check if new image is uploaded
        $image_name = ImageUpload($_FILES['image'], $errorMsg);
        if (!$image_name) {
            echo $errorMsg;
            exit;
        }
    } else {
        //  keep current image if no new image is uploaded
        $image_name = $_POST['current_image'];
    }
    // update book details
    $update = "UPDATE books SET 
                title = '$title', 
                description = '$description', 
                price = $price, 
                stock = $stock, 
                image = '$image_name', 
                category = '$category' 
               WHERE book_id = $book_id";

    if ($conn->query($update) === TRUE) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error updating book: " . $conn->error;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<?php if (isset($row)) { ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Edit Book - Book Haven</title>
        <link href="../css/style.css" rel="stylesheet">
    </head>

    <body>
        <?php include '../includes/header.php'; ?>
        <h1 class="title">Edit Book</h1>
        <section class="admin-section">
            <div class="admin-cards-container">
                <div class="admin-card" style="background:#fafdff;box-shadow:0 6px 24px rgba(52,152,219,0.07);border-radius:18px;padding:32px 24px 32px 24px;min-width:340px;max-width:540px;margin:auto;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <h3 style="margin:0;color:#3498db;font-size:1.3rem;letter-spacing:0.5px;">Edit Book Details</h3>
                    </div>
                    <form class="admin-form" method="POST" action="edit-book.php" enctype="multipart/form-data" style="background:#fff;padding:22px 20px 14px 20px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.06);display:flex;flex-direction:column;gap:16px;">
                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($row['image']); ?>">
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <label for="title" style="font-weight:600;color:#2980b9;">Book Title</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required style="padding:12px 14px;font-size:1.05rem;border-radius:7px;border:1px solid #e1e1e1;">
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <label for="description" style="font-weight:600;color:#2980b9;">Description</label>
                            <textarea id="description" name="description" required style="padding:12px 14px;font-size:1.05rem;border-radius:7px;border:1px solid #e1e1e1;resize:none;min-height:90px;"><?php echo htmlspecialchars($row['description']); ?></textarea>
                        </div>
                        <div style="display:flex;gap:16px;">
                            <div style="flex:1;display:flex;flex-direction:column;gap:6px;">
                                <label for="price" style="font-weight:600;color:#2980b9;">Price</label>
                                <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" min="0" step="0.01" required style="padding:12px 14px;font-size:1.05rem;border-radius:7px;border:1px solid #e1e1e1;">
                            </div>
                            <div style="flex:1;display:flex;flex-direction:column;gap:6px;">
                                <label for="stock" style="font-weight:600;color:#2980b9;">Stock Quantity</label>
                                <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($row['stock']); ?>" min="0" step="1" required style="padding:12px 14px;font-size:1.05rem;border-radius:7px;border:1px solid #e1e1e1;">
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <label for="category" style="font-weight:600;color:#2980b9;">Category</label>
                            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($row['category']); ?>" required style="padding:12px 14px;font-size:1.05rem;border-radius:7px;border:1px solid #e1e1e1;">
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <label for="image" style="font-weight:600;color:#2980b9;">Upload New Image</label>
                            <input type="file" id="image" name="image" accept="image/*" class="file-input" style="padding:14px 0 14px 0;background:#f8f9fa;border:1.5px dashed #3498db;border-radius:10px;transition:all 0.2s;cursor:pointer;font-size:1.05rem;">
                        </div>
                        <button type="submit" name="update" class="search-button" style="margin-top:8px;width:90%;max-width:400px;align-self:center;">Update Book</button>
                    </form>
                    <a href="dashboard.php" class="back-btn" style="display:inline-block;margin-top:18px;color:#3498db;font-weight:600;text-decoration:none;">&larr; Back to Dashboard</a>
                </div>
            </div>
        </section>
        <?php include '../includes/footer.php'; ?>
        <script src="../js/validation.js"></script>
    </body>

    </html>
<?php } ?>