<?php
session_start();
include('config.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$row = mysqli_fetch_assoc($result);

// Check if password is submitted, then hash and update only if needed
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Check if a new password is provided
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $hashedPassword, $email, $role, $id);
    } else {
        // Update without changing the password
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $role, $id);
    }
    $stmt->execute();

    header('Location: user_for_admin.php');
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
            z-index: 100;
        }

        .container1 {
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: stretch;
            margin: 50px auto;
            margin-right: 100px;
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

        nav ul li a:hover {
            background-color: #007BFF;
            color: white;
        }

        nav ul li a.active {
            background-color: #007BFF;
            color: white;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: auto;
        }

        h2 {
            margin-top: 0;
        }

        .dashboard-content {
            flex-grow: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            width: 300px;
        }

        form input, form select, form button {
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
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
        nav ul li a {
            display: flex;
            align-items: center;
            color: white;
            padding: 10px;
            border-radius: 4px;
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

    <div class="container">
        <form method="POST" action="" class="container1">
        <h3>Edit User</h3>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($row['username']); ?>" required>
            
            <label for="password">Password</label>
<input type="text" id="password" name="password" maxlength="15">


            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" required>

            <label for="role">Role</label>
            <select id="role" name="role">
                <option value="user" <?= $row['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>

            <button type="submit">Update User</button>
        </form>
        </div>
    </div>
</div>
</body>
</html>
