<?php
// Simple email viewer for .eml files
session_start();

$emailFile = $_GET['file'] ?? '';

if (empty($emailFile)) {
    header('Location: /fresit/');
    exit;
}

// Security: only allow .eml files from emails directory
$emailPath = __DIR__ . '/emails/' . basename($emailFile);
if (!file_exists($emailPath) || pathinfo($emailFile, PATHINFO_EXTENSION) !== 'eml') {
    header('Location: /fresit/');
    exit;
}

$emailContent = file_get_contents($emailPath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Viewer - Fresit Art School</title>
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
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
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
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📧 Email Viewer</h1>
            <p>Viewing: <?php echo htmlspecialchars(basename($emailFile)); ?></p>
        </div>
        
        <div class="email-content">
            <div class="email-body">
                <iframe srcdoc="<?php echo htmlspecialchars($emailContent); ?>"></iframe>
            </div>
        </div>
        
        <div class="email-actions">
            <a href="/fresit/student-login.php" class="btn">Login to Account</a>
            <a href="/fresit/" class="btn btn-secondary">Return to Home</a>
        </div>
    </div>
</body>
</html>
