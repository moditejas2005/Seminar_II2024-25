<?php
require_once 'config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';
$car = null;
$user = getCurrentUser();

// Get car details from URL parameter
if (isset($_GET['car_id'])) {
    $car_id = (int)$_GET['car_id'];
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ? AND status = 'available'");
        $stmt->execute([$car_id]);
        $car = $stmt->fetch();
        
        if (!$car) {
            $error = "Car not found or not available for purchase.";
        }
    } catch(PDOException $e) {
        $error = "Error loading car details.";
    }
} else {
    $error = "No car selected for purchase.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $car) {
    $payment_method = sanitizeInput($_POST['payment_method']);
    $delivery_address = sanitizeInput($_POST['delivery_address']);
    $delivery_date = sanitizeInput($_POST['delivery_date']);
    $notes = sanitizeInput($_POST['notes']);
    
    // Payment details
    $card_number = sanitizeInput($_POST['card_number'] ?? '');
    $card_holder = sanitizeInput($_POST['card_holder'] ?? '');
    $expiry_date = sanitizeInput($_POST['expiry_date'] ?? '');
    $cvv = sanitizeInput($_POST['cvv'] ?? '');
    $upi_id = sanitizeInput($_POST['upi_id'] ?? '');
    $bank_account = sanitizeInput($_POST['bank_account'] ?? '');
    $ifsc_code = sanitizeInput($_POST['ifsc_code'] ?? '');
    
    if (empty($payment_method) || empty($delivery_address) || empty($delivery_date)) {
        $error = "Please fill in all required fields.";
    } else {
        // Validate payment details based on method
        $payment_valid = true;
        $payment_details = [];
        
        switch($payment_method) {
            case 'credit_card':
            case 'debit_card':
                if (empty($card_number) || empty($card_holder) || empty($expiry_date) || empty($cvv)) {
                    $error = "Please fill in all card details.";
                    $payment_valid = false;
                } else {
                    $payment_details = [
                        'card_number' => substr($card_number, -4), // Store only last 4 digits
                        'card_holder' => $card_holder,
                        'expiry_date' => $expiry_date
                    ];
                }
                break;
            case 'upi':
                if (empty($upi_id)) {
                    $error = "Please enter UPI ID.";
                    $payment_valid = false;
                } else {
                    $payment_details = ['upi_id' => $upi_id];
                }
                break;
            case 'bank_transfer':
                if (empty($bank_account) || empty($ifsc_code)) {
                    $error = "Please fill in bank account details.";
                    $payment_valid = false;
                } else {
                    $payment_details = [
                        'bank_account' => $bank_account,
                        'ifsc_code' => $ifsc_code
                    ];
                }
                break;
            case 'cash':
                $payment_details = ['method' => 'cash_on_delivery'];
                break;
        }
        
        if ($payment_valid) {
            try {
                $pdo = getDBConnection();
                
                // Start transaction
                $pdo->beginTransaction();
                
                // Insert purchase record with payment status
                $stmt = $pdo->prepare("INSERT INTO purchases (user_id, car_id, total_amount, payment_method, payment_status, delivery_address, delivery_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$user['id'], $car['id'], $car['price'], $payment_method, 'completed', $delivery_address, $delivery_date, $notes]);
                
                // Update car status to sold
                $stmt = $pdo->prepare("UPDATE cars SET status = 'sold' WHERE id = ?");
                $stmt->execute([$car['id']]);
                
                // Commit transaction
                $pdo->commit();
                
                $success = "Purchase completed successfully! Your car will be delivered on " . $delivery_date;
                
            } catch(PDOException $e) {
                $pdo->rollback();
                $error = "Purchase failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Car - Cars24</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .purchase-container {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-color-dark));
            padding: 2rem;
        }
        
        .purchase-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: start;
        }
        
        .car-details {
            background: var(--white);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .car-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .car-info h2 {
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .car-price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .car-features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-light);
        }
        
        .purchase-form {
            background: var(--white);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .form-header h1 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
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
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
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
        
        .buyer-info {
            background: var(--extra-light);
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .buyer-info h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .buyer-detail {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .buyer-detail span:first-child {
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .buyer-detail span:last-child {
            color: var(--text-light);
        }
        
        .payment-form {
            background: var(--extra-light);
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid var(--primary-color);
            display: none;
        }
        
        .payment-form.active {
            display: block;
        }
        
        .payment-form h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-input-group {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 1rem;
        }
        
        .card-number-input {
            position: relative;
        }
        
        .card-number-input input {
            padding-right: 2.5rem;
        }
        
        .card-icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        
        .upi-info {
            background: var(--white);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .upi-info p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .bank-info {
            background: var(--white);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .bank-info p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .cash-info {
            background: var(--white);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .cash-info p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .purchase-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .card-input-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <a href="index.php" class="home-link">
        <i class="ri-arrow-left-line"></i>
        Back to Home
    </a>
    
    <div class="purchase-container">
        <?php if ($error): ?>
            <div class="error-message" style="max-width: 1200px; margin: 0 auto 2rem;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message" style="max-width: 1200px; margin: 0 auto 2rem;"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($car && $user): ?>
            <div class="purchase-content">
                <div class="car-details">
                    <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>" class="car-image">
                    
                    <div class="car-info">
                        <h2><?php echo htmlspecialchars($car['name']); ?></h2>
                        <div class="car-price">₹<?php echo number_format($car['price']); ?></div>
                        
                        <?php 
                        $features = json_decode($car['features'], true);
                        if ($features): 
                        ?>
                        <div class="car-features">
                            <?php foreach ($features as $key => $value): ?>
                                <div class="feature">
                                    <i class="ri-car-line"></i>
                                    <span><?php echo ucfirst($key) . ': ' . $value; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <p><?php echo htmlspecialchars($car['description']); ?></p>
                    </div>
                </div>
                
                <div class="purchase-form">
                    <div class="form-header">
                        <h1>Complete Purchase</h1>
                        <p>Fill in the details to complete your car purchase</p>
                    </div>
                    
                    <div class="buyer-info">
                        <h3>Buyer Information</h3>
                        <div class="buyer-detail">
                            <span>Name:</span>
                            <span><?php echo htmlspecialchars($user['full_name']); ?></span>
                        </div>
                        <div class="buyer-detail">
                            <span>Email:</span>
                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div class="buyer-detail">
                            <span>Phone:</span>
                            <span><?php echo htmlspecialchars($user['phone'] ?: 'Not provided'); ?></span>
                        </div>
                    </div>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="payment_method">Payment Method <span class="required">*</span></label>
                            <select id="payment_method" name="payment_method" required onchange="showPaymentForm()">
                                <option value="">Select payment method</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="debit_card">Debit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="upi">UPI</option>
                                <option value="cash">Cash on Delivery</option>
                            </select>
                        </div>
                        
                        <!-- Credit/Debit Card Payment Form -->
                        <div id="card-payment" class="payment-form">
                            <h3><i class="ri-bank-card-line"></i> Card Details</h3>
                            <div class="form-group">
                                <label for="card_holder">Card Holder Name <span class="required">*</span></label>
                                <input type="text" id="card_holder" name="card_holder" placeholder="Enter card holder name">
                            </div>
                            <div class="form-group">
                                <label for="card_number">Card Number <span class="required">*</span></label>
                                <div class="card-number-input">
                                    <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" oninput="formatCardNumber(this)">
                                    <i class="ri-bank-card-line card-icon"></i>
                                </div>
                            </div>
                            <div class="card-input-group">
                                <div class="form-group">
                                    <label for="expiry_date">Expiry Date <span class="required">*</span></label>
                                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" oninput="formatExpiry(this)">
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV <span class="required">*</span></label>
                                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                            </div>
                        </div>
                        
                        <!-- UPI Payment Form -->
                        <div id="upi-payment" class="payment-form">
                            <h3><i class="ri-smartphone-line"></i> UPI Payment</h3>
                            <div class="upi-info">
                                <p><strong>UPI ID Format:</strong> username@bankname (e.g., john@icici)</p>
                            </div>
                            <div class="form-group">
                                <label for="upi_id">UPI ID <span class="required">*</span></label>
                                <input type="text" id="upi_id" name="upi_id" placeholder="Enter your UPI ID">
                            </div>
                        </div>
                        
                        <!-- Bank Transfer Payment Form -->
                        <div id="bank-payment" class="payment-form">
                            <h3><i class="ri-bank-line"></i> Bank Transfer Details</h3>
                            <div class="bank-info">
                                <p><strong>Bank Details:</strong> Cars24 Bank, Account: 1234567890, IFSC: CARS0000123</p>
                            </div>
                            <div class="form-group">
                                <label for="bank_account">Your Bank Account Number <span class="required">*</span></label>
                                <input type="text" id="bank_account" name="bank_account" placeholder="Enter your bank account number">
                            </div>
                            <div class="form-group">
                                <label for="ifsc_code">IFSC Code <span class="required">*</span></label>
                                <input type="text" id="ifsc_code" name="ifsc_code" placeholder="Enter IFSC code">
                            </div>
                        </div>
                        
                        <!-- Cash on Delivery Payment Form -->
                        <div id="cash-payment" class="payment-form">
                            <h3><i class="ri-money-dollar-circle-line"></i> Cash on Delivery</h3>
                            <div class="cash-info">
                                <p><strong>Note:</strong> Payment will be collected in cash when the car is delivered. Please keep the exact amount ready.</p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="delivery_address">Delivery Address <span class="required">*</span></label>
                            <textarea id="delivery_address" name="delivery_address" placeholder="Enter your delivery address" required><?php echo htmlspecialchars($user['address'] ?: ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="delivery_date">Preferred Delivery Date <span class="required">*</span></label>
                            <input type="date" id="delivery_date" name="delivery_date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Additional Notes</label>
                            <textarea id="notes" name="notes" placeholder="Any special instructions or notes"></textarea>
                        </div>
                        
                        <button type="submit" class="btn" style="width: 100%;">Complete Purchase</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function showPaymentForm() {
            const paymentMethod = document.getElementById('payment_method').value;
            const paymentForms = document.querySelectorAll('.payment-form');
            
            // Hide all payment forms
            paymentForms.forEach(form => {
                form.classList.remove('active');
            });
            
            // Show the selected payment form
            if (paymentMethod === 'credit_card' || paymentMethod === 'debit_card') {
                document.getElementById('card-payment').classList.add('active');
            } else if (paymentMethod === 'upi') {
                document.getElementById('upi-payment').classList.add('active');
            } else if (paymentMethod === 'bank_transfer') {
                document.getElementById('bank-payment').classList.add('active');
            } else if (paymentMethod === 'cash') {
                document.getElementById('cash-payment').classList.add('active');
            }
        }
        
        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            input.value = value;
        }
        
        function formatExpiry(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            input.value = value;
        }
    </script>
</body>
</html> 