<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mentas.id</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column;
            background: linear-gradient(135deg, #d52c2c 0%, #8b1a1a 100%);
        }
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 8px;
        }
        .login-header p {
            color: #666;
        }
        .login-logo {
            font-size: 2rem;
            color: #d52c2c;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #d52c2c;
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #d52c2c;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background: #b52525;
        }
        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .login-footer a {
            color: #d52c2c;
            font-weight: 600;
            text-decoration: none;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-error { background: #f8d7da; color: #721c24; }
        .alert-success { background: #d4edda; color: #155724; }
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <a href="<?= BASE_URL ?>" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Website</a>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo"><i class="fas fa-user-circle"></i></div>
                <h1>Login</h1>
                <p>Masuk ke akun Mentas.id Anda</p>
            </div>

            <?php $flash = getFlash(); ?>
            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/auth/doLogin" method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="Masukkan email Anda">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password">
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="login-footer">
                <p>Belum punya akun? <a href="<?= BASE_URL ?>/auth/register">Daftar sebagai Contributor</a></p>
            </div>
        </div>
    </div>
</body>
</html>
