<?php
require_once '../config/database.php';
require_once '../models/Category.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$stmt = $category->getAll();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for adding new category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $category = new Category($db);
        
        if ($_POST['action'] === 'add') {
            $category->name = $_POST['name'];
            $category->icon = $_POST['icon'];
            $category->course_count = $_POST['course_count'];
            
            if ($category->create()) {
                $success_message = "Category created successfully.";
                // Refresh categories list
                $stmt = $category->getAll();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $error_message = "Unable to create category.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories Management - Admin Dashboard</title>
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

        .add-new-btn {
            background: #FF782D;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 14px;
        }

        .add-new-btn:hover {
            background: #ff6b1a;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .category-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .category-icon {
            width: 50px;
            height: 50px;
            background: #F8F9FA;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #FF782D;
        }

        .category-info h3 {
            font-size: 18px;
            color: #1D2026;
            margin-bottom: 5px;
        }

        .category-info p {
            font-size: 14px;
            color: #6D737A;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
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

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-primary {
            background: #FF782D;
            color: white;
        }

        .btn-secondary {
            background: #6D737A;
            color: white;
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

        .icon-preview {
            font-size: 24px;
            color: #FF782D;
            margin-top: 10px;
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
                <li><a href="index.php"><i class="fas fa-newspaper"></i> News</a></li>
                <li><a href="categories.php" class="active"><i class="fas fa-folder"></i> Categories</a></li>
                <li><a href="../home_page/home_page.php"><i class="fas fa-home"></i> View Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>Categories Management</h1>
                <button class="add-new-btn" onclick="openModal()"><i class="fas fa-plus"></i> Add Category</button>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <div class="categories-grid">
                <?php foreach($categories as $cat): ?>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="<?php echo $cat['icon']; ?>"></i>
                    </div>
                    <div class="category-info">
                        <h3><?php echo $cat['name']; ?></h3>
                        <p><?php echo $cat['course_count']; ?> Courses</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal" id="addCategoryModal">
        <div class="modal-content">
            <h2>Add New Category</h2>
            <form action="" method="POST">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="icon">Icon Class (Font Awesome)</label>
                    <input type="text" id="icon" name="icon" class="form-control" required 
                           placeholder="fas fa-code" onkeyup="previewIcon(this.value)">
                    <div class="icon-preview">
                        <i id="iconPreview" class=""></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="course_count">Number of Courses</label>
                    <input type="number" id="course_count" name="course_count" class="form-control" 
                           required min="0" value="0">
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openModal() {
        document.getElementById('addCategoryModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('addCategoryModal').classList.remove('active');
    }

    function previewIcon(iconClass) {
        document.getElementById('iconPreview').className = iconClass;
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        var modal = document.getElementById('addCategoryModal');
        if (event.target == modal) {
            closeModal();
        }
    }
    </script>
</body>
</html> 