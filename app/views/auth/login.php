<?php // Login Page - Uses existing Mentas design ?>

<section class="hero" style="padding: 100px 60px 40px;">
    <div class="hero-content">
        <h1>Login</h1>
        <p>Masuk ke akun Mentas.id Anda</p>
    </div>
</section>

<section class="program-section" style="flex-direction: column; align-items: center; padding: 40px 20px;">
    <div class="program-card" style="width: 100%; max-width: 420px; border-color: #d52c2c; box-shadow: 5px 5px 0 #d52c2c;">
        
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
            <div style="padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; background: <?= $flash['type'] === 'error' ? '#f8d7da' : '#d4edda' ?>; color: <?= $flash['type'] === 'error' ? '#721c24' : '#155724' ?>;">
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/doLogin" method="POST">
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">Email</label>
                <input type="email" name="email" required placeholder="Email Anda" style="width: 100%; padding: 14px; border: 2px solid #ddd; border-radius: 6px; font-size: 15px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">Password</label>
                <input type="password" name="password" required placeholder="Password Anda" style="width: 100%; padding: 14px; border: 2px solid #ddd; border-radius: 6px; font-size: 15px;">
            </div>

            <button type="submit" class="btn-orange" style="width: 100%; border: none; cursor: pointer; font-size: 16px;">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </button>
        </form>

        <div style="text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee;">
            <p>Belum punya akun? <a href="<?= BASE_URL ?>/auth/register" style="color: #d52c2c; font-weight: 600;">Daftar sebagai Contributor</a></p>
        </div>
    </div>
</section>

<style>
input:focus {
    outline: none;
    border-color: #d52c2c !important;
}
</style>
