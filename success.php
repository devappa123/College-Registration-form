<?php
// Get application ID from query parameter
$applicationId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted Successfully</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/style.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .success-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem 1rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .success-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 3rem 2rem;
            box-shadow: var(--shadow-xl);
            text-align: center;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #16a34a, #15803d);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: scaleIn 0.5s ease;
        }
        
        .success-icon i {
            font-size: 3rem;
            color: white;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .success-title {
            font-size: 2rem;
            color: var(--success-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .success-message {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .application-id-box {
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 2rem 0;
        }
        
        .application-id-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .application-id-value {
            font-size: 1.8rem;
            color: var(--primary-color);
            font-weight: 700;
            letter-spacing: 2px;
        }
        
        .info-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1rem 1.5rem;
            border-radius: 4px;
            margin: 2rem 0;
            text-align: left;
        }
        
        body.dark-mode .info-box {
            background: #422006;
            border-left-color: #f59e0b;
        }
        
        .info-box i {
            color: #f59e0b;
            margin-right: 0.5rem;
        }
        
        .info-box p {
            margin: 0.5rem 0;
            color: var(--text-primary);
            font-size: 0.95rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        
        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }
        
        .btn-secondary:hover {
            background: #475569;
        }
        
        @media (max-width: 768px) {
            .success-card {
                padding: 2rem 1.5rem;
            }
            
            .success-title {
                font-size: 1.5rem;
            }
            
            .application-id-value {
                font-size: 1.4rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Dark Mode Toggle -->
    <div class="theme-toggle">
        <i class="fas fa-moon" id="themeIcon"></i>
    </div>

    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <h1 class="success-title">Application Submitted Successfully!</h1>
            
            <p class="success-message">
                Thank you for applying to our Engineering College. Your application has been received and is being processed.
            </p>
            
            <div class="application-id-box">
                <div class="application-id-label">Your Application ID</div>
                <div class="application-id-value"><?php echo $applicationId; ?></div>
            </div>
            
            <div class="info-box">
                <p><i class="fas fa-info-circle"></i> <strong>Important:</strong></p>
                <p>• Please save your Application ID for future reference</p>
                <p>• You will receive a confirmation email shortly</p>
                <p>• Admission team will contact you within 3-5 business days</p>
                <p>• Keep checking your email for further updates</p>
            </div>
            
            <div class="action-buttons">
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="fas fa-print"></i> Print Confirmation
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Theme Toggle
        document.querySelector('.theme-toggle').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            const icon = document.getElementById('themeIcon');
            
            if (document.body.classList.contains('dark-mode')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                localStorage.setItem('theme', 'dark');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
                localStorage.setItem('theme', 'light');
            }
        });
        
        // Load theme preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            document.getElementById('themeIcon').classList.remove('fa-moon');
            document.getElementById('themeIcon').classList.add('fa-sun');
        }
    </script>
</body>
</html>
