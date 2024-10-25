<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

include('config.php');
$userId = $_SESSION['user_id']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];

    $stmt = $conn->prepare("INSERT INTO employees (name, email, position, salary, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdi", $name, $email, $position, $salary, $userId);
    $stmt->execute();

    header('Location: employee_index.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $employeeId = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $employeeId, $userId);
    $stmt->execute();

    header('Location: employee_index.php');
    exit();
}
$employeesResult = $conn->prepare("SELECT * FROM employees WHERE user_id = ?");
$employeesResult->bind_param("i", $userId);
$employeesResult->execute();
$result = $employeesResult->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css_styles.css">
    <title>Employees</title>
    <style>
        .hidden { display: none; }
        .button-group button { margin-right: 10px; }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: auto;
            max-width: 800px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: stretch;
            margin: 50px auto 0;
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

        .dashboard-content {
            padding: 40px;
        }

        h2 {
            margin-top: 0;
        }

        .table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        thead {
            background-color: black;
        }

        th {
            color: white;
            text-align: left;
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
        <div class="dashboard-content">
            <div class="button-group">
                <button id="show-employee-overview" class="nav-link active">Employee Overview</button>
                <button id="show-add-employee" class="nav-link">Add Employee</button>
            </div>

            <div id="employee-overview" class="content-section">
                <h3>Employee Overview</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['name']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= htmlspecialchars($row['position']); ?></td>
                                <td><?= htmlspecialchars($row['salary']); ?></td>
                                <td>
                                    <a href="employees_edit.php?id=<?= $row['id']; ?>">Edit</a>
                                    <a href="employee_index.php?action=delete&id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div id="add-employee-form" class="content-section hidden">
                <div class = "container1">
                <h3>Add New Employee</h3>
                <form method="POST" action="">
                    <label>Name</label>
                    <input type="text" name="name" required>
                    <label>Email</label>
                    <input type="email" name="email" required>
                    <label>Position</label>
                    <input type="text" name="position" required>
                    <label>Salary</label>
                    <input type="number" name="salary" step="0.01" required>
                    <button type="submit">Add Employee</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const overviewSection = document.getElementById('employee-overview');
            const addEmployeeForm = document.getElementById('add-employee-form');
            const showOverviewBtn = document.getElementById('show-employee-overview');
            const showAddEmployeeBtn = document.getElementById('show-add-employee');

            showOverviewBtn.addEventListener('click', function () {
                addEmployeeForm.classList.add('hidden');
                overviewSection.classList.remove('hidden');
                showOverviewBtn.classList.add('active');
                showAddEmployeeBtn.classList.remove('active');
            });

            showAddEmployeeBtn.addEventListener('click', function () {
                overviewSection.classList.add('hidden');
                addEmployeeForm.classList.remove('hidden');
                showAddEmployeeBtn.classList.add('active');
                showOverviewBtn.classList.remove('active');
            });
        });
    </script>
</body>
</html>
