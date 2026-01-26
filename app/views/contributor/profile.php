<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="container contributor-form-page">
    <div class="page-header">
        <h1 class="page-title">Edit Profil</h1>
        <a href="<?= BASE_URL ?>/contributor" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <?php $flash = getFlash(); ?>
    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?>">
            <?= $flash['message'] ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form action="<?= BASE_URL ?>/contributor/updateProfile" method="POST" enctype="multipart/form-data">
            
            <!-- SECTION 1: IDENTITY (Side-by-Side) -->
            <div class="form-section identity-section">
                <!-- Left: Avatar -->
                <div class="identity-left">
                    <div class="avatar-wrapper">
                        <?php 
                            $avatar = !empty($user['avatar']) ? BASE_URL . '/' . $user['avatar'] : 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=random&size=200';
                        ?>
                        <img src="<?= $avatar ?>" id="avatar-preview-img">
                        <label for="avatar-input" class="avatar-upload-btn">
                            <i class="fa-solid fa-camera"></i> Ubah Foto
                        </label>
                        <input type="file" name="avatar" id="avatar-input" class="visually-hidden" accept="image/*" onchange="previewAvatar(this)">
                    </div>
                    <p class="avatar-help">Format: JPG, PNG (Max 2MB)</p>
                </div>

                <!-- Right: Basic Info -->
                <div class="identity-right">
                    <h3 class="section-heading">Informasi Dasar</h3>
                    <div class="main-info-grid">
                        <div class="form-group">
                            <label for="name">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" class="form-control disabled-input" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="phone">No. Telepon / WhatsApp</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group span-full">
                            <label for="bio">Biografi Singkat</label>
                            <textarea id="bio" name="bio" class="form-control" rows="3" placeholder="Ceritakan pengalaman Anda..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group span-full">
                            <label for="address">Alamat Lengkap</label>
                            <textarea id="address" name="address" class="form-control" rows="2" placeholder="Alamat lengkap..."><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: SOCIAL MEDIA & SECURITY (Grid) -->
            <div class="bottom-sections-grid">
                <!-- Socials -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-share-nodes"></i> Social Media</h3>
                    <?php $socials = json_decode($user['social_media'] ?? '[]', true); ?>
                    
                    <div class="form-group">
                        <label><i class="fa-solid fa-globe"></i> Website</label>
                        <input type="url" name="website" class="form-control" placeholder="https://" value="<?= htmlspecialchars($socials['website'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fa-brands fa-instagram"></i> Instagram</label>
                        <input type="text" name="instagram" class="form-control" placeholder="@username" value="<?= htmlspecialchars($socials['instagram'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fa-brands fa-facebook"></i> Facebook</label>
                        <input type="text" name="facebook" class="form-control" placeholder="Link Profile" value="<?= htmlspecialchars($socials['facebook'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label><i class="fa-brands fa-twitter"></i> Twitter / X</label>
                        <input type="text" name="twitter" class="form-control" placeholder="Link Profile" value="<?= htmlspecialchars($socials['twitter'] ?? '') ?>">
                    </div>
                </div>

                <!-- Security -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-lock"></i> Keamanan</h3>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ubah">
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <div class="form-actions sticky-actions">
                <button type="submit" class="btn btn-primary btn-save">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</main>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview-img').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
.contributor-form-page { max-width: 1300px; padding: 40px; margin: 0 auto; width: 100%; }

/* Header */
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #eee; }
.page-title { font-size: 2rem; font-weight: 800; color: #333; margin: 0; }
.btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; background: #fff; border: 1px solid #ddd; color: #555; text-decoration: none; border-radius: 50px; font-weight: 600; transition: all 0.2s; font-size: 0.95rem; }
.btn-back:hover { background: #f0f0f0; color: #333; transform: translateX(-3px); }

/* Form Card */
.form-card { background: #fff; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #eaeaea; }

/* Layout Identity */
.identity-section { display: flex; gap: 60px; padding: 50px; border-bottom: 1px solid #f0f0f0; }
.identity-left { width: 250px; flex-shrink: 0; text-align: center; border-right: 1px solid #f5f5f5; padding-right: 60px; }
.identity-right { flex: 1; min-width: 0; }

/* Avatar */
.avatar-wrapper { position: relative; width: 180px; height: 180px; margin: 0 auto 20px; }
.avatar-wrapper img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.avatar-upload-btn { position: absolute; bottom: 5px; right: 5px; background: #d52c2c; color: #fff; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; border: 3px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
.avatar-upload-btn:hover { background: #b01b1b; transform: scale(1.1); }
.visually-hidden { display: none; }
.avatar-help { font-size: 0.85rem; color: #888; margin-top: 15px; }

/* Layout Bottom */
.bottom-sections-grid { display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid #f0f0f0; }
.form-section { padding: 40px 50px; }
.bottom-sections-grid .form-section:first-child { border-right: 1px solid #f0f0f0; }

/* Common Types */
.section-heading { font-size: 1.3rem; font-weight: 700; color: #222; margin-bottom: 25px; padding-bottom: 12px; border-bottom: 2px solid #d52c2c; display: inline-block; }
.section-title { font-size: 1.2rem; font-weight: 700; color: #333; margin-bottom: 25px; display: flex; align-items: center; gap: 12px; }
.section-title i { color: #d52c2c; font-size: 1.3rem; }

/* Inputs */
.main-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
.span-full { grid-column: 1 / -1; }
.form-group { margin-bottom: 25px; }
.form-group label { display: block; margin-bottom: 10px; font-weight: 600; font-size: 0.95rem; color: #444; }
.form-control { width: 100%; padding: 14px 16px; border: 1px solid #e0e0e0; border-radius: 10px; font-size: 1rem; background: #fdfdfd; transition: all 0.2s; }
.form-control:focus { outline: none; border-color: #d52c2c; background: #fff; box-shadow: 0 0 0 4px rgba(213, 44, 44, 0.05); }
.disabled-input { background: #f5f5f5; color: #999; cursor: not-allowed; }
.required { color: #d52c2c; }

/* Actions */
.form-actions { background: #fafafa; padding: 25px 50px; text-align: right; }
.btn-save { padding: 14px 35px; font-size: 1.05rem; }

/* Responsive */
@media (max-width: 900px) {
    .identity-section { flex-direction: column; gap: 30px; text-align: center; }
    .identity-left { width: 100%; border-right: none; border-bottom: 1px solid #eee; padding-right: 0; padding-bottom: 30px; }
    .bottom-sections-grid { grid-template-columns: 1fr; }
    .bottom-sections-grid .form-section:first-child { border-right: none; border-bottom: 1px solid #f0f0f0; }
}
@media (max-width: 600px) {
    .main-info-grid { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: flex-start; gap: 15px; }
    .page-title { font-size: 1.5rem; }
    .btn-back { font-size: 0.85rem; padding: 10px 15px; }
    .contributor-form-page { padding: 20px 15px; }
    .form-section { padding: 30px 20px; }
}
</style>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
