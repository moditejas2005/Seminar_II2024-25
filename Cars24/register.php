<?php
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitizeInput($_POST['full_name']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    
    // Comprehensive Validation
    $errors = [];
    
    // Username validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    } elseif (strlen($username) > 20) {
        $errors[] = "Username must be less than 20 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores.";
    }
    
    // Email validation
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    } elseif (strlen($email) > 100) {
        $errors[] = "Email address is too long.";
    }
    
    // Full name validation
    if (empty($full_name)) {
        $errors[] = "Full name is required.";
    } elseif (strlen($full_name) < 2) {
        $errors[] = "Full name must be at least 2 characters long.";
    } elseif (strlen($full_name) > 50) {
        $errors[] = "Full name must be less than 50 characters.";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $full_name)) {
        $errors[] = "Full name can only contain letters and spaces.";
    }
    
    // Phone validation (optional but if provided, validate format)
    if (!empty($phone)) {
        // Remove all non-digit characters for validation
        $phone_clean = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone_clean) !== 10) {
            $errors[] = "Phone number must be exactly 10 digits.";
        }
    }
    
    // Address validation (optional but if provided, validate length)
    if (!empty($address)) {
        if (strlen($address) < 10) {
            $errors[] = "Address must be at least 10 characters long.";
        } elseif (strlen($address) > 200) {
            $errors[] = "Address must be less than 200 characters.";
        }
    }
    
    // Password validation
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } elseif (strlen($password) > 50) {
        $errors[] = "Password must be less than 50 characters.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).";
    }
    
    // Confirm password validation
    if (empty($confirm_password)) {
        $errors[] = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    
    // If no validation errors, proceed with registration
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();
            
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $errors[] = "Username already exists. Please choose a different one.";
            } else {
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $errors[] = "Email already registered. Please use a different email or login.";
                } else {
                    // Hash password and insert user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$username, $email, $hashed_password, $full_name, $phone, $address]);
                    
                    $success = "Registration successful! You can now login.";
                    
                    // Clear form data after successful registration
                    $username = $email = $full_name = $phone = $address = '';
                }
            }
        } catch(PDOException $e) {
            $errors[] = "Registration failed. Please try again.";
        }
    }
    
    // Set error message if there are validation errors
    if (!empty($errors)) {
        $error = implode(" ", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cars24</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-color-dark));
            padding: 2rem;
        }
        
        .auth-form {
            background: var(--white);
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-header h1 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .auth-header p {
            color: var(--text-light);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 500;
        }
        
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .error-message {
            background: #fee;
            color: #e74c3c;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #fcc;
        }
        
        .success-message {
            background: #efe;
            color: #27ae60;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #cfc;
        }
        
        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .auth-links a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .auth-links a:hover {
            text-decoration: underline;
        }
        
        .home-link {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: var(--white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }
        
        .home-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <a href="index.php" class="home-link">
        <i class="ri-arrow-left-line"></i>
        Back to Home
    </a>
    
    <div class="auth-container">
        <div class="auth-form">
            <div class="auth-header">
                <h1>Create Account</h1>
                <p>Join Cars24 to buy your dream car</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="full_name">Full Name <span class="required">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn" style="width: 100%;">Create Account</button>
            </form>
            
            <div class="auth-links">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html> 