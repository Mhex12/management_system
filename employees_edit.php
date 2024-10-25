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

    $stmt = $conn->prepare("UPDATE employees SET name = ?, email = ?, position = ?, salary = ? WHERE id = ?");
    $stmt->bind_param("sssdi", $name, $email, $position, $salary, $id);
    $stmt->execute();


    header('Location: employee_index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css_styles.css">
    <title>Edit Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: black;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            margin-right: 170px;
        }

        h1 {
            color: #FFF100;
            margin-left: 40px;
        }

        .dashboard-content {
            flex-grow: 1;
            padding: 40px;
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
</body>
</html>
