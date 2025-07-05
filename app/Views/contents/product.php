<?= $this->extend('layouts/index') ?>
<?= $this->section('content') ?>

<header class="topbar d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
    <h2 class="text-white fw-semibold text-uppercase small select-none mb-0">Produk</h2>
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
            <div class="text-uppercase mb-4 text-secondary small fw-semibold select-none">
                Data Produk
            </div>

            <div class="d-flex justify-content-between mb-3">
                <form class="d-flex" role="search" method="get" action="<?= site_url('produk') ?>">
                    <input class="form-control me-2" type="search" placeholder="Cari Produk" name="q" aria-label="Search">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Cari</button>
                </form>

                <div>
                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#productModal">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#stockModal">
                        <i class="fas fa-plus"></i> Tambah Stok
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php $no = 1;
                            foreach ($products as $p): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($p['name']) ?></td>
                                    <td><?= esc($p['quantity']) ?></td>
                                    <td>Rp <?= number_format($p['bay'], 0, ',', '.') ?></td>
                                    <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                    <td>
                                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editModalProduk<?= $p['id'] ?>"><i class="fas fa-edit text-primary fs-5"></i></button>
                                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editModalStok<?= $p['id'] ?>"><i class="fas fa-box-open text-info fs-5"></i></button>
                                        <a href="<?= site_url('product/delete/' . $p['id']) ?>" class="btn" onclick="return confirm('Yakin ingin menghapus produk ini?')"><i class="fas fa-trash text-danger fs-5"></i></a>
                                    </td>
                                </tr>


                                <!-- Modal Edit Barang -->
                                <div class="modal fade" id="editModalProduk<?= $p['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?= site_url('product/update/' . $p['id']) ?>" method="post">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="stockModalLabel">Edit </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama_barang" class="form-label">Nama Barang</label>
                                                        <input type="text" class="form-control" id="nama_barang" name="name" value="<?= $p['name']; ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="kategori" class="form-label">Kategori</label>
                                                        <select id="kategori" name="category_id" class="form-control select2">
                                                            <?php foreach ($categories as $c): ?>
                                                                <option value="<?= $c['id'] ?>" <?= (isset($p['category_id']) && $p['category_id'] == $c['id']) ? 'selected' : '' ?>>
                                                                    <?= esc($c['name']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>

                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                                        <input type="text" class="form-control" id="deskripsi" name="description" value="<?= $p['description']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga_beli" class="form-label">Harga Beli</label>
                                                        <input type="number" class="form-control" id="harga_beli" name="bay" value="<?= $p['bay']; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga_jual" class="form-label">Harga Jual</label>
                                                        <input type="number" class="form-control" id="harga_jual" name="price" value="<?= $p['price']; ?>">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- modal edit stok -->

                                <div class="modal fade editProductModal" id="editModalStok<?= $p['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?= site_url('product/stok/update/' . $p['id']) ?>" method="post">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="stockModalLabel">Edit Jumlah Stok</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <select id="stok" name="product_id" class="form-control select2">
                                                            <?php foreach ($stoks as $s): ?>
                                                                <option value="<?= esc($s['product_id']) ?>"><?= esc($p['name']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="stok_jumlah" class="form-label">Total Stok</label>
                                                        <input type="number" id="stok_jumlah" name="quantity" class="form-control" value="<?= esc($p['quantity']); ?>">
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada produk yang tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('product/add') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select id="kategori" name="category_id" class="form-control select2" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= esc($c['id']) ?>"><?= esc($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsi" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga_beli" class="form-label">Harga Beli</label>
                        <input type="number" class="form-control" id="harga_beli" name="bay" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga_jual" class="form-label">Harga Jual</label>
                        <input type="number" class="form-control" id="harga_jual" name="price" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Tambah Stok -->
<div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('product/stock') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockModalLabel">Tambah Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="produk_id" class="form-label">Nama Barang</label>
                        <select id="produk_id" name="product_id" class="form-control select2" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= esc($p['id']) ?>"><?= esc($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="stok_jumlah" class="form-label">Jumlah Stok</label>
                        <input type="number" id="stok_jumlah" name="quantity" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->endSection() ?>