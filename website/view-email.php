<?php
//.eml files
session_start();

$emailType = $_GET['type'] ?? '';
$emailId = $_GET['id'] ?? '';
$emailFile = $_GET['file'] ?? '';

// Handle new URL format
if (!empty($emailType) && !empty($emailId)) {
    $emailFile = $emailType . '-' . $emailId . '.eml';
}

if (empty($emailFile)) {
    header('Location: /royaldrawingschool/');
    exit;
}

// Security: only allow .eml files from emails directory
$emailPath = __DIR__ . '/emails/' . basename($emailFile);
if (!file_exists($emailPath) || pathinfo($emailFile, PATHINFO_EXTENSION) !== 'eml') {
    header('Location: /royaldrawingschool/');
    exit;
}

$emailContent = file_get_contents($emailPath);

// Determine email type for display
$isApplication = strpos($emailFile, 'application-') === 0;
$emailTypeDisplay = $isApplication ? 'Application Confirmation' : 'Registration Confirmation';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $emailTypeDisplay; ?> - Royal Drawing School</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .email-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .email-header {
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .email-content {
            padding: 20px;
        }
        .email-actions {
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .button:hover {
            background: #0056b3;
        }
        .button-secondary {
            background: #6c757d;
        }
        .button-secondary:hover {
            background: #545b62;
        }
        .button-success {
            background: #28a745;
        }
        .button-success:hover {
            background: #218838;
        }
        .email-body {
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            margin: 20px 0;
            min-height: 400px;
        }
        iframe {
            width: 100%;
            height: 500px;
            border: none;
        }
        .email-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
        }
        .email-info p {
            margin: 5px 0;
            color: #1976d2;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📧 <?php echo $emailTypeDisplay; ?></h1>
            <p>Viewing: <?php echo htmlspecialchars(basename($emailFile)); ?></p>
        </div>
        
        <div class="email-content">
            <div class="email-info">
                <p><strong>Email Type:</strong> <?php echo $emailTypeDisplay; ?></p>
                <p><strong>File:</strong> <?php echo htmlspecialchars(basename($emailFile)); ?></p>
                <p><strong>Generated:</strong> <?php echo date('F j, Y \a\t g:i A', filemtime($emailPath)); ?></p>
            </div>
            
            <div class="email-body">
                <iframe srcdoc="<?php echo htmlspecialchars($emailContent); ?>"></iframe>
            </div>
        </div>
        
        <div class="email-actions">
            <?php if ($isApplication): ?>
                <a href="/royaldrawingschool/booking.php" class="button button-success">Book Another Class</a>
                <a href="/royaldrawingschool/student-login.php" class="button">Student Login</a>
            <?php else: ?>
                <a href="/royaldrawingschool/student-login.php" class="button">Login to Account</a>
            <?php endif; ?>
            <a href="/royaldrawingschool/" class="button button-secondary">Return to Home</a>
        </div>
    </div>
</body>
</html>
