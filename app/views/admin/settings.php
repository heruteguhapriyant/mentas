<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="page-header">
    <h1>Pengaturan Akun</h1>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/settingsUpdate" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label>Avatar</label>
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                <?php if ($user['avatar']): ?>
                    <img src="<?= BASE_URL ?>/<?= $user['avatar'] ?>" alt="" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                <?php else: ?>
                    <div style="width: 80px; height: 80px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user" style="font-size: 2rem; color: #ccc;"></i>
                    </div>
                <?php endif; ?>
            </div>
            <input type="file" name="avatar" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label>Nama *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
        </div>

        <hr style="margin: 2rem 0;">
        <h4 style="margin-bottom: 1rem;">Ubah Password</h4>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">Kosongkan jika tidak ingin mengubah password</p>

        <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="password" class="form-control" minlength="6">
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirm" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
    </form>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
