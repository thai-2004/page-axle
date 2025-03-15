<?php
require_once '../config/database.php';
require_once '../models/News.php';
require_once '../models/Category.php';
require_once '../helpers/ImageHelper.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$categories = $category->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news = new News($db);
    
    // Handle file upload
    $new_image = ImageHelper::uploadImage($_FILES["image"]);
    if ($new_image) {
        $news->title = $_POST['title'];
        $news->description = $_POST['description'];
        $news->image = $new_image;
        $news->author = $_POST['author'];
        $news->category_id = $_POST['category_id'];
        $news->views = 0;

        if ($news->create()) {
            $success_message = "News article created successfully.";
        } else {
            $error_message = "Unable to create news article.";
        }
    } else {
        $error_message = "Error uploading image. Allowed types: JPG, JPEG, PNG, GIF";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Article - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #1D2026;
            color: white;
            padding: 20px;
        }

        .sidebar .logo {
            margin-bottom: 30px;
        }

        .sidebar .logo img {
            height: 40px;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 10px;
        }

        .sidebar-menu a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #FF782D;
        }

        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background: #F8F9FA;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 24px;
            color: #1D2026;
        }

        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1D2026;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #E0E0E0;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea.form-control {
            height: 150px;
            resize: vertical;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #FF782D;
            color: white;
        }

        .btn-secondary {
            background: #6D737A;
            color: white;
            margin-right: 10px;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background: #22C55E;
            color: white;
        }

        .alert-danger {
            background: #EF4444;
            color: white;
        }

        .preview-image {
            display: none;
            margin-top: 10px;
        }

        .preview-image img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 5px;
            object-fit: cover;
            border: 1px solid #E0E0E0;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="logo">
                <img src="../assets/LOGO.jpg" alt="EduPress Logo">
            </div>
            <ul class="sidebar-menu">
                <li><a href="index.php" class="active"><i class="fas fa-newspaper"></i> News</a></li>
                <li><a href="categories.php"><i class="fas fa-folder"></i> Categories</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> View Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>Add New Article</h1>
            </div>
            
            <div class="form-container">
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php while ($row = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required onchange="previewImage(this)">
                        <small>Allowed types: JPG, JPEG, PNG, GIF</small>
                        <div class="preview-image">
                            <p>Preview:</p>
                            <img id="preview" src="#" alt="Preview">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function previewImage(input) {
        var preview = document.getElementById('preview');
        var previewContainer = document.querySelector('.preview-image');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            previewContainer.style.display = 'none';
        }
    }
    </script>
</body>
</html> 