<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include('config.php');

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $checkStmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username already taken, please choose another one.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        if ($stmt->execute()) {
            header('Location: user_for_admin.php');
            exit();
        } else {
            $error = "Error adding user: " . $stmt->error;
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM users");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Users</title>
    <style>
        .hidden { display: none; }
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
        }
        .button-group button.active {
            background-color: #007BFF;
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
        td a {
            color: green; 
            margin-right: 10px; 
            text-decoration: none; 
            display: center;
        }

        td a:hover {
            color: #007BFF; /* Change color on hover */
        }
      
    </style>
</head>
<div>
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
                <button id="show-user-overview" class="nav-link">User Overview</button>
                <button id="show-add-user" class="nav-link">Add User</button>
            </div>

            <div id="user-overview" class="hidden">
            <div class="container1"> 
                <h3>User Overview</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= htmlspecialchars($row['role']); ?></td>

                                <td class="icon-center">
                                    <a href="admin_user_edit.php?id=<?= $row['id']; ?>" title="Edit">
                                        <i class="fas fa-edit" style="color: green;"></i>
                                    </a>
                                    <a href="users_delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')" title="Delete">
                                        <i class="fas fa-trash-alt" style="color: red;"></i>
                                    </a>
                                </td>


                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            </div>

            <div id="add-user-form" class="hidden">
            <div class = "container1">
                <h3>Add New User</h3>
                <?php if (isset($error)) { ?>
                    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
                <?php } ?>
                <form method="POST" action="">
                    <label>Username</label>
                    <input type="text" name="username" required>
                    <label>Email</label>
                    <input type="email" name="email" required>
                    <label>Password</label>
                    <input type="password" name="password" required>
                    <label>Role</label>
                    <select name="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit">Add User</button>
                </form>
            </div>
        </div>
    </div>
                </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Cache DOM elements for user overview and user form
            const userOverviewSection = document.getElementById('user-overview');
            const addUserForm = document.getElementById('add-user-form');
            const showUserOverviewBtn = document.getElementById('show-user-overview');
            const showAddUserBtn = document.getElementById('show-add-user');

            // Initial state - show user overview by default
            userOverviewSection.classList.remove('hidden');
            addUserForm.classList.add('hidden');

            // Event listener to show user overview section
            showUserOverviewBtn.addEventListener('click', function () {
                addUserForm.classList.add('hidden'); 
                userOverviewSection.classList.remove('hidden'); 
                showUserOverviewBtn.classList.add('active');
                showAddUserBtn.classList.remove('active');
            });

            // Event listener to show add user form
            showAddUserBtn.addEventListener('click', function () {
                userOverviewSection.classList.add('hidden'); 
                addUserForm.classList.remove('hidden');
                showAddUserBtn.classList.add('active');
                showUserOverviewBtn.classList.remove('active');
            });
        });
    </script>
</body>
</html>
