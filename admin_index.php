<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];

 

    // Adjust query to include the password column
    $stmt = $conn->prepare("INSERT INTO employees (name, email, position, salary) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $name, $email, $position, $salary);
    $stmt->execute();

    header('Location: admin_index.php');
    exit();
}

// Delete Employee
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();

    header('Location: admin_index.php');
    exit();
}

$employeesResult = mysqli_query($conn, "SELECT * FROM employees");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Employees</title>
    <style>
        .hidden { 
            display: none; 
        }

        .button-group {
            display: flex;                 
            margin-bottom: 20px;          
        }

        .button-group button {
            background-color: black;   
            color: white;                 
            padding: 7px 15px;          
            margin-right: 10px;            
            border: none;                  
            border-radius: 5px;         
            cursor: pointer;             
            font-size: 16px;           
            transition: background-color 0.3s ease, color 0.3s ease; 
        }

        .button-group button:hover {
            background-color: #444;       
            color: white;                 
        }

        .button-group button.active {
            background-color: #007BFF;     
            color: white;                
        }


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

        .container {
            display: flex;
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
        }

        nav ul li a.active {
            background-color: #007BFF;
        }


        .dashboard-content {
            flex-grow: 1; 
            padding: 40px; 
            margin-left: 300px;
            margin-bottom: 90px;
        }


        .button-group {
            margin-top: 10px;
        }

        h2 {
            margin-top: 0;
        }
        thead{
            background-color: black;
        }
        th {
            color: white; 
            text-align: left; 
        }

        .table {
            width: 100%;
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

        form input, form select, form button {
            margin-bottom: 5px;
            padding: 6px;
            width: 98%;
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
        td a {
            color: green; 
            margin-right: 15px; 
            text-decoration: none; 
            display: center;
        }

        td a:hover {
            color: #007BFF; 
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
            <div class="button-group">
                <button id="show-employee-overview" class="nav-link">Employee Overview</button>
                <button id="show-add-employee" class="nav-link">Add Employee</button>
            </div>

            <div id="employee-overview" class="hidden">
                <div class="container1">
                <h3>Employee Overview</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Salary</th>
                            <th>Actions</th>
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
                                <td class="icon-center">
                                    <a href="admin_edit.php?id=<?= $row['id']; ?>" title="Edit">
                                        <i class="fas fa-edit" style="color: green;"></i>
                                    </a>
                                    <a href="admin_employees_delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')" title="Delete">
                                        <i class="fas fa-trash-alt" style="color: red;"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            </div>

            <div id="add-employee-form" class="hidden">
            <div class="container1">
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
                    <button type="submit" name="add_employee">Add Employee</button>
                </form>
            </div>
        </div>
    </div>

   <script>
    document.addEventListener('DOMContentLoaded', function () {
    const employeeOverviewSection = document.getElementById('employee-overview');
    const addEmployeeForm = document.getElementById('add-employee-form'); // Ensure this ID matches your form
    const showEmployeeOverviewBtn = document.getElementById('show-employee-overview'); // Ensure this ID matches your button
    const showAddEmployeeBtn = document.getElementById('show-add-employee'); // Ensure this ID matches your button

    // Initial state - show employee overview by default
    employeeOverviewSection.classList.remove('hidden');
    addEmployeeForm.classList.add('hidden');

    showEmployeeOverviewBtn.addEventListener('click', function () {
        addEmployeeForm.classList.add('hidden'); 
        employeeOverviewSection.classList.remove('hidden'); 
        showEmployeeOverviewBtn.classList.add('active');
        showAddEmployeeBtn.classList.remove('active');
    });

    showAddEmployeeBtn.addEventListener('click', function () {
        employeeOverviewSection.classList.add('hidden'); 
        addEmployeeForm.classList.remove('hidden');
        showAddEmployeeBtn.classList.add('active');
        showEmployeeOverviewBtn.classList.remove('active');
    });
});

</script>

</body>
</html>



