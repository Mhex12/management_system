<?php
session_start();
include('config.php');

$error = ""; 
$stmt = null; 
$conn = null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'management_system'); // Database connection

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; 

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username']; 
            $_SESSION['role'] = $user['role'];

        
            if (strtolower($user['role']) === 'admin') {
                header("Location: dashboard_admin.php"); 
                exit();
            } else {
                header("Location: employee_index.php"); 
            }
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "User not found. Please try again.";
    }
    $_SESSION['error'] = $error;
    header("Location: login.php");
    exit();
}
if ($stmt) {
    $stmt->close();
}
if ($conn) {
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
        }

        .container {
            display: flex;
            width: 80%;
            max-width: 700px;
            height: 350px;
            border-radius: 8px;
            box-shadow: 0 0 20px 10px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            position: relative;
        }

        .left-section {
            background-color: #fff;
            position: relative;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;   
            z-index: 2;    
        }
        .left-section h2 {
            margin-top: 50px;
            margin-left: 70px;
        }

        .right-section {
            background-color: #000;
            color: #fff;
            padding: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: right;
            clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 70% 100%);
        }

        .right-section h2{
            font-size: 25px;
            margin-bottom: 50px;
            font-weight: bold;
            color: white;
            margin-right: 0px;
        }
        .right-section p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 40px;
            margin-left: 100px; 
        }

        h2 {
            margin-bottom: 50px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            text-align: left;
            margin-bottom: 8px;
            color: #555;
        }

        .input-container {
            position: relative;
            margin-bottom: 10px;
        }
        .input-container select {
            width: 200%; /* Full width like the input fields */
            padding: 10px 30px 10px 30px;
            border: none;
            border-bottom: 2px solid #ccc;
            outline: none;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            appearance: none; /* Remove default arrow icon */
            background: none; /* Transparent background */
            padding-left: 40px;
        }
        .input-container input[type="text"],
        .input-container input[type="password"] {
            width: 200%;
            padding: 10px 30px 10px 30px;
            border: none;
            border-bottom: 2px solid #ccc;
            outline: none;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .input-container i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            font-size: 18px;
            color: #555;
        }
        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #999; 
            padding-left: 10px;
        }
        .input-container select {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="none" stroke="%23ccc" stroke-width="1px" d="M2 0 L0 2 L4 2 Z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 10px;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            padding-left: 30px;
            border-bottom: 2px solid #000
        }
        .input-container select:focus {
            border-bottom: 5px solid #000;
        }

        .input-container select option[disabled] {
            color: #999;
        }
        .input-container .fa-lock {
            left: 10px; /* Position for the lock icon */
        }

        .input-container .fa-eye {
            right: 10px; /* Position for the eye icon */
        }

        button {
            background-color: #000;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            width: 200%;
            font-size: 16px;
            cursor: pointer;
        }

        select option[value=""] {
            color: #999; 
        }

        select:invalid {
            color: #999; 
        }

        select option {
            color: #000; 
        }


        button:hover {
            background-color: #333;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <form method="POST" action="">
                <h2>Login</h2>
                <div class="input-container">
                    <input type="text" id="username" name="username" placeholder="Username" required>
                    <i class="fa fa-user"></i>
                </div>
                <div class="input-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <i class="fa fa-lock"></i> 
                    <ii class="fa fa-eye" id="togglePassword" style="cursor: pointer; position: absolute; left: 240px; top: 50%; transform: translateY(-50%);"></ii> 
                </div>


                <div class="input-container">
                    <select id="role" name="role" required>
                        <option value="" disabled selected>Select user</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <i class="fa fa-users"></i> 
                </div>
                
                <button type="submit">Login</button>

                <?php if (isset($_SESSION['error'])): ?>
                    <p class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></p>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
            </form>
        </div>

        <div class="right-section">
            <h2>EMPLOYEE MANAGEMENT <br>SYSTEM!</h2>
            <p>Welcome Back!</p>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = this;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye'); 
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash'); 
                toggleIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</div>
</body>
</html>
