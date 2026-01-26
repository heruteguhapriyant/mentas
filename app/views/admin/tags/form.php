<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="page-header">
    <h1><?= $tag ? 'Edit Tag' : 'Tambah Tag Baru' ?></h1>
    <a href="<?= BASE_URL ?>/admin/tags" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <form action="<?= BASE_URL ?>/admin/<?= $tag ? 'tagUpdate/' . $tag['id'] : 'tagStore' ?>" method="POST">
        <div class="form-group">
            <label for="name">Nama Tag *</label>
            <input type="text" id="name" name="name" class="form-control" required 
                   value="<?= htmlspecialchars($tag['name'] ?? '') ?>"
                   placeholder="Contoh: Seni Rupa, Teater, Musik">
        </div>

        <div class="form-group">
            <label for="slug">Slug (opsional)</label>
            <input type="text" id="slug" name="slug" class="form-control" 
                   value="<?= htmlspecialchars($tag['slug'] ?? '') ?>"
                   placeholder="Otomatis di-generate jika kosong">
            <small class="form-text">URL-friendly version dari nama tag</small>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
