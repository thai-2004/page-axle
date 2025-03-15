<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - News Management</title>
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
        }

        .add-new-btn:hover {
            background: #ff6b1a;
        }

        .news-table {
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .news-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .news-table th,
        .news-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #E0E0E0;
        }

        .news-table th {
            background: #F8F9FA;
            font-weight: 600;
            color: #1D2026;
        }

        .news-table tr:hover {
            background: #F8F9FA;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .edit-btn,
        .delete-btn {
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            color: white;
            font-size: 14px;
        }

        .edit-btn {
            background: #22C55E;
        }

        .delete-btn {
            background: #EF4444;
        }

        .edit-btn:hover,
        .delete-btn:hover {
            opacity: 0.9;
        }

        .thumbnail {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <?php
    require_once '../config/database.php';
    require_once '../models/News.php';
    require_once '../models/Category.php';

    $database = new Database();
    $db = $database->getConnection();

    $news = new News($db);
    $stmt = $news->getAll(); // We'll add this method to the News class
    $news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    
    <div class="admin-container">
        <div class="sidebar">
            <div class="logo">
                <img src="../assets/LOGO.jpg" alt="EduPress Logo">
            </div>
            <ul class="sidebar-menu">
                <li><a href="index.php" class="active"><i class="fas fa-newspaper"></i> News</a></li>
                <li><a href="categories.php"><i class="fas fa-folder"></i> Categories</a></li>
                <li><a href="../home_page/home_page.php"><i class="fas fa-home"></i> View Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>News Management</h1>
                <a href="add_news.php" class="add-new-btn"><i class="fas fa-plus"></i> Add New</a>
            </div>
            
            <div class="news-table">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Views</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($news_items as $item): ?>
                        <tr>
                            <td><img src="../assets/news/<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" class="thumbnail"></td>
                            <td><?php echo $item['title']; ?></td>
                            <td><?php echo $item['category_name']; ?></td>
                            <td><?php echo $item['author']; ?></td>
                            <td><?php echo $item['views']; ?></td>
                            <td><?php echo date('d M Y', strtotime($item['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_news.php?id=<?php echo $item['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i></a>
                                    <a href="delete_news.php?id=<?php echo $item['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this news item?');"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 