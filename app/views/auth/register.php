<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Mentas.id</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/mentas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column;
            background: linear-gradient(135deg, #d52c2c 0%, #8b1a1a 100%);
        }
        .register-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .register-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 600px;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-header h1 {
            font-size: 1.6rem;
            color: #333;
            margin-bottom: 8px;
        }
        .register-header p {
            color: #666;
        }
        .register-logo {
            font-size: 2rem;
            color: #d52c2c;
            margin-bottom: 15px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #d52c2c;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .btn-register {
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
            margin-top: 10px;
        }
        .btn-register:hover {
            background: #b52525;
        }
        .register-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .register-footer a {
            color: #d52c2c;
            font-weight: 600;
            text-decoration: none;
        }
        .register-note {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #888;
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
        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
            .register-card { padding: 30px 20px; }
        }
    </style>
</head>
<body>
    <a href="<?= BASE_URL ?>" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Website</a>
    
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="register-logo"><i class="fas fa-user-plus"></i></div>
                <h1>Daftar sebagai Contributor</h1>
                <p>Bergabunglah untuk berbagi karya seni dan budaya</p>
            </div>

            <?php $flash = getFlash(); ?>
            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/auth/doRegister" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="name" required placeholder="Nama lengkap Anda">
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required placeholder="Email aktif">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter" minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password *</label>
                        <input type="password" name="password_confirm" required placeholder="Ulangi password">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="tel" name="phone" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="form-group">
                        <label>Foto Profil</label>
                        <input type="file" name="avatar" accept="image/*">
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="address" placeholder="Kota/Daerah Anda">
                </div>

                <div class="form-group">
                    <label>Bio / Tentang Anda</label>
                    <textarea name="bio" placeholder="Ceritakan sedikit tentang diri Anda dan minat di bidang seni/budaya"></textarea>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>

            <div class="register-footer">
                <p>Sudah punya akun? <a href="<?= BASE_URL ?>/auth/login">Login</a></p>
            </div>

            <div class="register-note">
                <small>* Setelah mendaftar, akun Anda akan direview oleh admin sebelum dapat digunakan.</small>
            </div>
        </div>
    </div>
</body>
</html>
