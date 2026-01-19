<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="container error-page">
    <div class="error-content">
        <h1>404</h1>
        <p>Halaman tidak ditemukan</p>
        <a href="<?= BASE_URL ?>" class="btn btn-primary">Kembali ke Home</a>
    </div>
</main>

<style>
.error-page { min-height: 60vh; display: flex; align-items: center; justify-content: center; }
.error-content { text-align: center; }
.error-content h1 { font-size: 6rem; color: #d52c2c; margin-bottom: 0; }
.error-content p { font-size: 1.5rem; color: #666; margin-bottom: 2rem; }
.btn-primary { background: #d52c2c; color: #fff; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
