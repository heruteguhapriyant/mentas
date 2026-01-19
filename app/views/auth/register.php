<?php // Register Page - Uses existing Mentas design ?>

<section class="hero" style="padding: 100px 60px 40px;">
    <div class="hero-content">
        <h1>Daftar sebagai Contributor</h1>
        <p>Bergabunglah untuk berbagi karya seni dan budaya</p>
    </div>
</section>

<section class="program-section" style="flex-direction: column; align-items: center; padding: 40px 20px;">
    <div class="program-card" style="width: 100%; max-width: 650px; border-color: #d52c2c; box-shadow: 5px 5px 0 #d52c2c;">
        
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
            <div style="padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; background: <?= $flash['type'] === 'error' ? '#f8d7da' : '#d4edda' ?>; color: <?= $flash['type'] === 'error' ? '#721c24' : '#155724' ?>;">
                <?= $flash['message'] ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/doRegister" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600;">Nama Lengkap *</label>
                    <input type="text" name="name" required placeholder="Nama lengkap Anda" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600;">Email *</label>
                    <input type="email" name="email" required placeholder="Email aktif" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600;">Password *</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600;">Konfirmasi Password *</label>
                    <input type="password" name="password_confirm" required placeholder="Ulangi password" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600;">No. Telepon</label>
                    <input type="tel" name="phone" placeholder="08xxxxxxxxxx" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-weight: 600;">Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; background: #fff;">
                </div>
            </div>

            <div style="margin-top: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">Alamat</label>
                <input type="text" name="address" placeholder="Kota/Daerah Anda" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>

            <div style="margin-top: 15px;">
                <label style="display: block; margin-bottom: 6px; font-weight: 600;">Bio / Tentang Anda</label>
                <textarea name="bio" rows="3" placeholder="Ceritakan sedikit tentang diri Anda dan minat di bidang seni/budaya" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;"></textarea>
            </div>

            <button type="submit" class="btn-orange" style="width: 100%; margin-top: 20px; border: none; cursor: pointer; font-size: 16px;">
                <i class="fa-solid fa-user-plus"></i> Daftar
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
            <p>Sudah punya akun? <a href="<?= BASE_URL ?>/auth/login" style="color: #d52c2c; font-weight: 600;">Login</a></p>
        </div>

        <div style="text-align: center; margin-top: 15px; font-size: 13px; color: #888;">
            <small>* Setelah mendaftar, akun Anda akan direview oleh admin sebelum dapat digunakan.</small>
        </div>
    </div>
</section>

<style>
input:focus, textarea:focus {
    outline: none;
    border-color: #d52c2c !important;
}
@media (max-width: 600px) {
    .program-section > div > form > div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
