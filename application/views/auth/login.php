<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<script>
<?php if ($this->session->userdata('admin_logged_in')): ?>
    window.location.href = "<?= base_url('audit'); ?>";
<?php endif; ?>
</script>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Audit Trail System</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --secondary: #8b5cf6;
    --success: #10b981;
    --danger: #ef4444;
    --dark: #1e293b;
    --light: #f8fafc;
    --glass: rgba(255, 255, 255, 0.1);
}

body {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

/* Animated Background */
.bg-animation {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.bg-animation span {
    position: absolute;
    display: block;
    width: 20px;
    height: 20px;
    background: rgba(255, 255, 255, 0.1);
    animation: float 25s infinite;
    border-radius: 50%;
}

.bg-animation span:nth-child(1) { left: 10%; animation-delay: 0s; }
.bg-animation span:nth-child(2) { left: 20%; animation-delay: 2s; animation-duration: 20s; }
.bg-animation span:nth-child(3) { left: 30%; animation-delay: 4s; }
.bg-animation span:nth-child(4) { left: 40%; animation-delay: 6s; animation-duration: 18s; }
.bg-animation span:nth-child(5) { left: 50%; animation-delay: 8s; }
.bg-animation span:nth-child(6) { left: 60%; animation-delay: 10s; animation-duration: 22s; }
.bg-animation span:nth-child(7) { left: 70%; animation-delay: 12s; }
.bg-animation span:nth-child(8) { left: 80%; animation-delay: 14s; animation-duration: 26s; }

@keyframes float {
    0% {
        transform: translateY(100vh) scale(0);
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        transform: translateY(-100vh) scale(1);
        opacity: 0;
    }
}

/* Login Container */
.login-container {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 440px;
    padding: 20px;
}

.login-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    padding: 48px 40px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Logo & Header */
.login-header {
    text-align: center;
    margin-bottom: 40px;
}

.logo-circle {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.logo-circle svg {
    width: 40px;
    height: 40px;
    fill: white;
}

.login-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 8px;
}

.login-header p {
    color: #64748b;
    font-size: 15px;
}

/* Form */
.form-group {
    margin-bottom: 24px;
    position: relative;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--dark);
    font-size: 14px;
}

.input-wrapper {
    position: relative;
}

.input-wrapper svg {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    fill: #94a3b8;
    transition: fill 0.3s;
}

.form-control {
    width: 100%;
    padding: 14px 16px 14px 48px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}

.form-control:focus + svg {
    fill: var(--primary);
}

/* Button */
.btn-login {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    margin-top: 8px;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
}

.btn-login:active {
    transform: translateY(0);
}

/* Alert */
.alert {
    position: fixed;
    top: 24px;
    right: 24px;
    padding: 16px 24px;
    border-radius: 12px;
    background: white;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 9999;
    animation: slideInRight 0.4s ease-out;
    max-width: 400px;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alert-danger {
    border-left: 4px solid var(--danger);
    color: var(--danger);
}

.alert-danger::before {
    content: '⚠';
    font-size: 20px;
}

/* Responsive */
@media (max-width: 480px) {
    .login-card {
        padding: 32px 24px;
    }
    
    .login-header h1 {
        font-size: 24px;
    }
    
    .alert {
        left: 16px;
        right: 16px;
        max-width: none;
    }
}
</style>
</head>

<body>

<!-- Background Animation -->
<div class="bg-animation">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>

<!-- Alert -->
<?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<!-- Login Container -->
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-circle">
                <svg viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
            <h1>Audit Trail System</h1>
            <p>Masuk untuk melanjutkan ke dashboard</p>
        </div>

        <form action="<?= base_url('auth/login_process'); ?>" method="post">
            <div class="form-group">
                <label>Username atau Email</label>
                <div class="input-wrapper">
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username atau email" required autofocus>
                    <svg viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    <svg viewBox="0 0 24 24">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                    </svg>
                </div>
            </div>

            <button type="submit" class="btn-login">Masuk ke Dashboard</button>
        </form>
    </div>
</div>

<script>
// Auto hide alert after 5 seconds
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
        alert.style.animation = 'slideInRight 0.4s ease-out reverse';
        setTimeout(() => alert.remove(), 400);
    }
}, 5000);
</script>

</body>
</html>