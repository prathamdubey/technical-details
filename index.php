<?php
// Start the session to maintain login state
session_start();

// Function to detect if user is on mobile device
function isMobile() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
    // Common mobile device identifiers
    $mobileKeywords = array(
        'Android', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone',
        'Opera Mini', 'Mobile', 'Kindle', 'Silk', 'Nokia', 'Samsung'
    );
    
    // Check if user agent contains mobile keywords
    foreach ($mobileKeywords as $keyword) {
        if (stripos($userAgent, $keyword) !== false) {
            return true;
        }
    }
    
    // Additional check for viewport width using JavaScript
    echo '<script>
        if (window.innerWidth <= 768) {
            document.cookie = "is_mobile=1; path=/";
        } else {
            document.cookie = "is_mobile=0; path=/";
        }
    </script>';
    
    // Check if cookie says it's mobile
    if (isset($_COOKIE['is_mobile']) && $_COOKIE['is_mobile'] == '1') {
        return true;
    }
    
    return false;
}

// Set correct password
$correct_password = "Pratham@1234";
$page_to_protect = "tech-details.html";
$error_message = "";
$is_mobile = isMobile();

// Process login form if submitted
if (isset($_POST['submit_password'])) {
    $submitted_password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if ($submitted_password === $correct_password) {
        // Store authentication in session
        $_SESSION['authenticated'] = true;
        
        // Redirect to the protected page
        header("Location: $page_to_protect");
        exit;
    } else {
        $error_message = "Incorrect password. Please try again.";
    }
}

// Check if user is already authenticated
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header("Location: $page_to_protect");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Protected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;
            max-width: 400px;
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .mobile-message {
            color: #e74c3c;
            font-size: 18px;
            line-height: 1.6;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .error {
            color: #e74c3c;
            margin-bottom: 15px;
        }
        
        .icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($is_mobile): ?>
            <!-- Mobile device message -->
            <div class="icon">⚠️</div>
            <h1>Device Not Supported</h1>
            <p class="mobile-message">
                This website content is made for desktop view and is not suitable for mobile devices. 
                Please access this page from a desktop or laptop computer.
            </p>
        <?php else: ?>
            <!-- Password protection form -->
            <div class="icon">🔒</div>
            <h1>Password Protected</h1>
            <p>Please enter the password to access this content.</p>
            
            <?php if (!empty($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form method="post" action="">
                <div class="form-group">
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>
                <button type="submit" name="submit_password">Submit</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>