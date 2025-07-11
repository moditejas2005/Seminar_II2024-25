<?php 
session_start(); 
$users = [ 
'admin' => 'admin123', 
'user' => 'password' 
]; 
// logout 
if (isset($_POST['logout'])) { 
session_destroy(); 
header("Location: ".$_SERVER['PHP_SELF']); 
exit; 
} 
// login 
$error = ''; 
if (isset($_POST['username']) && isset($_POST['password'])) { 
$username = trim($_POST['username']); 
$password = trim($_POST['password']); 
if (isset($users[$username]) && $users[$username] === $password) { 
$_SESSION['username'] = $username; 
header("Location: ".$_SERVER['PHP_SELF']); 
exit; 
} else { 
$error = "Invalid username or password."; 
} 
} 
$isLoggedIn = isset($_SESSION['username']); 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <title>PHP Sign-In / Sign-Out</title> 
    <style> 
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, #1abc9c, #16a085); 
            color: white; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        } 
        .container { 
            background: rgba(255,255,255,0.1); 
            padding: 30px; 
            border-radius: 12px; 
            text-align: center; 
            width: 300px; 
            box-shadow: 0 0 15px rgba(0,0,0,0.3); 
        } 
        h2 { 
            margin-bottom: 20px; 
        } 
        input { 
            padding: 10px; 
            width: 90%; 
            margin: 10px 0; 
            border: none; 
            border-radius: 5px; 
            outline: none; 
        } 
        button { 
            padding: 10px 20px; 
            border: none; 
            background: #e74c3c; 
            color: white; 
            border-radius: 5px; 
            cursor: pointer; 
            margin-top: 10px; 
            transition: background 0.3s ease; 
        } 
        button:hover { 
            background: #c0392b; 
        } 
        .logout-btn { 
            background: #27ae60; 
        } 
        .error { 
            color: #f1c40f; 
        } 
    </style> 
</head> 
<body> 
<div class="container"> 
    <?php if ($isLoggedIn): ?> 
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2> 
        <form method="post"> 
            <button type="submit" name="logout" class="logout-btn">Sign Out</button> 
        </form> 
<?php else: ?> 
<h2>Sign In</h2> 
<?php if ($error): ?> 
<p class="error"><?php echo $error; ?></p> 
<?php endif; ?> 
<form method="post"> 
<input type="text" name="username" placeholder="Username" required><br> 
<input type="password" name="password" placeholder="Password" required><br> 
<button type="submit">Sign In</button> 
</form> 
<?php endif; ?> 
</div> 
</body> 
</html>