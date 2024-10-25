<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];

    $stmt = $conn->prepare("INSERT INTO employees (name, email, position, salary) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $name, $email, $position, $salary);
    $stmt->execute();

    header('Location: admin_index.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $employeeId = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();

    header('Location: admin_index.php');
    exit();
}

$employeesResult = mysqli_query($conn, "SELECT * FROM employees");
$usersResult = mysqli_query($conn, "SELECT * FROM users");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Employees & Users</title>
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
            z-index: 100; 
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

        .container1 {
            width: 100%;
            max-width: 900px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            padding: 20px;
            margin-bottom: 20px;
            margin-left: 200px;
        }

        h2 {
            margin-top: 0;
        }

        thead {
            background-color: black;
        }

        th {
            color: white;
            text-align: left;
        }

        table {
            width: 100%;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form input, form select, form button {
            margin-bottom: 10px;
            padding: 8px;
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

    <div class="main-content">
        <div class="container1">
            <h3>User Overview</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($usersResult)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['username']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['role']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="container1">
            <h3>Employee Overview</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($employeesResult)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['position']); ?></td>
                            <td><?= htmlspecialchars($row['salary']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
