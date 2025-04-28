<?php
include '../includes/db.php';

if (isset($_GET['id'])) {
    $book_id = (int) $_GET['id'];
    $result = $conn->query("SELECT * FROM books WHERE book_id = $book_id");

    if ($result && $row = $result->fetch_assoc()) {
        // book found
    } else {
        echo "Book not found.";
        exit;
    }
} elseif (isset($_POST['update'])) {
    $book_id = (int) $_POST['book_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $category = $conn->real_escape_string($_POST['category']);

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "../assets/images/";
        $target_file = $target_dir . $image_name;
        $allowed_types = ['jpg', 'jpeg', 'png'];

        $file_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed_types)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        } else {
            echo "Only JPG, JPEG, and PNG files are allowed.";
            exit;
        }
    } else {
        //  if no new image is uploaded, keep the current image
        $image_name = $_POST['current_image'];
    }

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
        <title>Edit Book</title>
        <link href="../css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>

    <body>

        <?php include '../includes/header.php'; ?>

        <div class="edit-container">
            <h2>Edit Book</h2>
            <form method="POST" action="edit-book.php" enctype="multipart/form-data">
                <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($row['image']); ?>">

                <label>Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>

                <label>Description:</label>
                <textarea name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea>

                <label>Price:</label>
                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required>

                <label>Stock:</label>
                <input type="number" name="stock" value="<?php echo htmlspecialchars($row['stock']); ?>" required>

                <label>Category:</label>
                <input type="text" name="category" value="<?php echo htmlspecialchars($row['category']); ?>" required>

                <label>Upload New Image:</label>
                <input type="file" name="image" accept=".jpg, .jpeg, .png">

                <button type="submit" name="update">Update Book</button>
            </form>

            <a href="dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>

    </body>

    </html>
<?php } ?>