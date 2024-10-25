<?php
session_start();
include('config.php');
$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM employees WHERE id = $id");
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE employees SET name = ?, email = ?, position = ?, salary = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $position, $salary, $id);

    $stmt->execute();

    header('Location: admin_index.php');
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
    <title>Edit Employee</title>
    <style>
        .hidden { display: none; }
        .button-group button { margin-right: 10px; }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container1 {
            width: 200%;
            max-width: 900px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: stretch;
            margin: 50px auto 0;
            margin-right: 70px;
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

        .table {
            width: 70%;
            border-collapse: collapse;
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

        form input, form button {
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

        <div class="container1">
            <h3>Edit Employee</h3>
            <form method="POST" action="">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($row['name']); ?>" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" required>
                
                <label for="position">Position</label>
                <input type="text" id="position" name="position" value="<?= htmlspecialchars($row['position']); ?>" required>
                
                <label for="salary">Salary</label>
                <input type="number" id="salary" name="salary" value="<?= htmlspecialchars($row['salary']); ?>" step="0.01" required>
                
                <button type="submit">Update Employee</button>
            </form>
        </div>
    </div>
</body>
</html>
