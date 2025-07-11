# 🚗 Cars24 – Car Buying Website

A full-featured car purchasing platform built using **PHP**, **MySQL**, **HTML**, **CSS**, and **JavaScript**. Designed for user-friendly browsing, secure purchasing, and smooth database interaction.

---

## 📌 Features

- 🔐 User Registration & Login with session management  
- 🚘 Browse cars by brand (Tesla, Toyota, Mazda, etc.)  
- 🧾 Purchase cars with delivery & payment details  
- 🗂️ Database integration (MySQL) for users, cars & orders  
- 📱 Responsive and mobile-first design  
- 🎨 Modern and intuitive UI

---

## 🗂️ File Structure

```
Cars24/
├── assets/                 # Images and static assets
├── config/
│   └── database.php       # Database configuration
├── index.html             # Main homepage (static version)
├── index.php              # Main homepage (dynamic version)
├── login.php              # User login page
├── register.php           # User registration page
├── purchase.php           # Car purchase form
├── logout.php             # User logout
├── database.sql           # Database schema and sample data
├── buy-now.js             # JavaScript for buy functionality
├── main.js                # Main JavaScript file
├── styles.css             # Main stylesheet
└── README.md              # This file
```

## ⚙️ Setup Instructions

### 1. Database Setup

1. **Install XAMPP** (or any local server with PHP and MySQL)
2. **Start Apache and MySQL** services
3. **Create Database**:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the `database.sql` file
   - This will create the `cars24_db` database with all necessary tables

### 2. File Setup

1. **Place files** in your XAMPP htdocs folder:
   ```
   C:\xampp\htdocs\Cars24\
   ```

2. **Configure Database** (if needed):
   - Edit `config/database.php`
   - Update database credentials if different from default

### 3. Access the Website

1. **Open your browser** and go to:
   ```
   http://localhost/Cars24/
   ```

2. **For dynamic version** (recommended):
   ```
   http://localhost/Cars24/index.php
   ```

## 🧬 Database Schema

### Users Table
- `id` - Primary key
- `username` - Unique username
- `email` - Unique email address
- `password` - Hashed password
- `full_name` - User's full name
- `phone` - Phone number
- `address` - User address
- `created_at` - Registration timestamp

### Cars Table
- `id` - Primary key
- `name` - Car name
- `brand` - Car brand
- `model` - Car model
- `year` - Manufacturing year
- `price` - Car price
- `image` - Car image path
- `description` - Car description
- `features` - JSON features
- `rating` - Average rating
- `reviews_count` - Number of reviews
- `status` - Available/Sold/Reserved

### Purchases Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `car_id` - Foreign key to cars
- `purchase_date` - Purchase timestamp
- `total_amount` - Purchase amount
- `payment_method` - Payment method used
- `payment_status` - Payment status
- `delivery_address` - Delivery address
- `delivery_date` - Preferred delivery date
- `notes` - Additional notes

## 👣 User Flow

1. **Registration**: New users create an account
2. **Login**: Users authenticate to access purchase features
3. **Browse Cars**: View cars by brand category
4. **Select Car**: Click "Buy Now" on desired car
5. **Purchase Form**: Fill in delivery and payment details
6. **Complete Purchase**: Car status updates to "sold"

## Features in Detail

### Registration System
- Username and email uniqueness validation
- Password confirmation
- Form validation and error handling
- Secure password hashing

### Login System
- Username or email login
- Session management
- Secure authentication
- Automatic redirect after login

### Purchase System
- Auto-filled buyer information
- Multiple payment methods
- Delivery address and date selection
- Purchase confirmation
- Database transaction handling

### Car Management
- Dynamic car loading from database
- Brand-based categorization
- Real-time availability status
- Feature display with icons

## 🔐 Security Features

- **Password Hashing**: Uses PHP's `password_hash()` function
- **SQL Injection Prevention**: Prepared statements
- **XSS Prevention**: Input sanitization
- **Session Management**: Secure session handling
- **Input Validation**: Server-side validation

## 🎨 Customization

### ➕ Adding New Cars
1. Add car data to the `cars` table
2. Include car image in `assets/` folder
3. Update features JSON as needed

### 🎨 Modifying Styles
- Edit `styles.css` for visual changes
- Update color variables in CSS root

### 🧩 Adding Features
- Extend database schema as needed
- Update PHP files for new functionality
- Modify JavaScript for enhanced interactions

## 🛠️ Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Check if MySQL is running
   - Verify database credentials in `config/database.php`
   - Ensure database `cars24_db` exists

2. **Page Not Loading**:
   - Check if Apache is running
   - Verify file permissions
   - Check for PHP syntax errors

3. **Images Not Displaying**:
   - Ensure `assets/` folder contains all images
   - Check file paths in HTML

4. **Purchase Not Working**:
   - Verify user is logged in
   - Check database permissions
   - Review PHP error logs

## 🌐 Browser Support

- ✅Chrome (recommended)
- ✅Firefox
- ✅Safari
- ✅Edge

## 💻 Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Icons**: Remix Icons
- **Sliders**: Swiper.js
- **Animations**: ScrollReveal

## License

This project is for educational purposes. Feel free to modify and use as needed.

## Support

For issues or questions, please check the troubleshooting section above or review the code comments for guidance. 