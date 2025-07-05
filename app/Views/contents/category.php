<?= $this->extend('layouts/index') ?>
<?= $this->section('content') ?>

<header class="topbar d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
    <h2 class="text-white fw-semibold text-uppercase small select-none mb-0">Kategori</h2>
</header>

<?php if (session()->getFlashdata('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.success("<?= esc(session()->getFlashdata('success'), 'js') ?>");
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.error("<?= esc(session()->getFlashdata('error'), 'js') ?>");
        });
    </script>
<?php endif; ?>

<section class="p-4 bg-white w-100">
    <div class="rounded-3 shadow-lg p-4 bg-white">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-uppercase text-secondary small fw-semibold mb-0 select-none">Daftar Kategori</h3>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus me-1"></i> Tambah Kategori
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light text-uppercase small">
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= esc($category['id']) ?></td>
                                    <td><?= esc($category['name']) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= $category['id'] ?>">Edit</button>
                                        <form action="<?= base_url('category/delete/' . $category['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted fst-italic">Tidak ada kategori yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('category/add') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label for="categoryName" class="form-label">Nama Kategori</label>
                    <input type="text" class="form-control" id="categoryName" name="name" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<?php foreach ($categories as $category): ?>
    <div class="modal fade" id="editCategoryModal<?= $category['id'] ?>" tabindex="-1" aria-labelledby="editCategoryModalLabel<?= $category['id'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= base_url('category/update/' . $category['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel<?= $category['id'] ?>">Edit Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" name="name" value="<?= esc($category['name']) ?>" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
    // Auto hide toastr alert jika ada (dari flashdata)
    setTimeout(() => {
        document.querySelectorAll('.toast, .alert').forEach(el => {
            el.classList.remove('show');
        });
    }, 3000);
</script>

<?= $this->endSection() ?>
