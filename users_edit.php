<?php
session_start();
include('config.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0; 

$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);
    $stmt->execute();

    header('Location: users_index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Edit User</title>
    <style>
        .hidden { display: none; }
        .button-group button { margin-right: 10px; }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column; 
            height: 100vh; 
        }

        header {
            background-color: black;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            color: white;
            margin-left: 40px;
        }

        .container {
            display: flex;
            flex: 1;
            overflow: auto;
        }

        nav {
            width: 200px;
            background-color: black;
            padding: 15px;
            position: fixed;
            top: 0; 
            bottom: 0; 
            height: 100%;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            margin-bottom: 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 10px;
            display: block;
            border-radius: 4px;
        }

        nav ul li a:hover, nav ul li a.active {
            background-color: #007BFF;
            color: white;
        }

        .dashboard-content {
            flex-grow: 1;
            padding: 40px;
            margin-left: 220px; 
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form input, form select, form button {
            margin-bottom: 10px;
            padding: 8px;
        }

        form button {
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        nav ul li a i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Employee Management System</h1>
        <div class="user-info">
            Welcome, <?= htmlspecialchars($_SESSION['username']); ?> | <a href="logout.php" style="color: yellow;">Logout</a>
        </div>
    </header> 

    <div class="container">
        <nav>
            <ul>
                <li>
                    <a href="dashboard_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard_admin.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="admin_index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin_index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Employees
                    </a>
                </li>
                <li>
                    <a href="user_for_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'user_for_admin.php' ? 'active' : ''; ?>">
                        <i class="fas fa-user-cog"></i> Users
                    </a>
                </li>
            </ul>
        </nav>

        <div class="dashboard-content">
            <h3>Edit User</h3>
            <form method="POST" action="">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($row['username']); ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" required>

                <label>Role</label>
                <select name="role">
                    <option value="user" <?= $row['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
                <button type="submit">Update User</button>
            </form>
        </div>
    </div>
</body>
</html>
