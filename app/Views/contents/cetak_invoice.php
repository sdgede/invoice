<?= $this->extend('layouts/index') ?>
<?= $this->section('content') ?>


<header class="topbar d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
    <h2 class="text-white fw-semibold text-uppercase small select-none mb-0">
        Cetak
    </h2>
</header>

<!-- Content Section -->
<section class="p-4 bg-white w-100">
    <div class="rounded-3 shadow-lg p-4 bg-white">
        <div class="mb-4">
            <div class="text-uppercase mb-4 text-secondary small fw-semibold select-none">
                Data Invoice
            </div>

            <!-- Search + Button -->
            <div class="d-flex justify-content-between mb-3">
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Cari Order  " aria-label="Search">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">Cari</button>
                </form>

            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">ID</th>
                            <th scope="col">Code</th>
                            <th scope="col">Nama Pelanggan</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Diskon</th>
                            <th scope="col">Status</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($invoices)): ?>
                            <?php foreach ($invoices as $row): ?>
                                <?php
                                $subtotal = $row['quantity'] * $row['price'];
                                $totalHarga = $subtotal - $row['discount'];
                                ?>
                                <tr class="text-center">
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['code_invoice'] ?></td>
                                    <td><?= $row['customer_name'] ?></td>
                                    <td><?= esc($row['name']) ?></td>
                                    <td><?= esc($row['quantity']) ?></td>
                                    <td><?= esc($row['description']) ?></td>
                                    <td>Rp <?= number_format($row['discount'], 0, ',', '.') ?></td>
                                    <td><?= $row['status'] ?></td>
                                    <td>Rp <?= number_format($totalHarga, 0, ',', '.') ?></td>
                                    <td>
                                        <button class="btn" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= $row['id'] ?>"><i class="fas fa-edit text-primary fs-5"></i></button>
                                        <form action="<?= base_url('category/delete/' . $row['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus invoice ini?')">
                                            <?= csrf_field() ?>
                                            <button class="btn"><i class="fas fa-trash text-danger fs-5"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted fst-italic">Tidak ada invoice yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Invoice -->
<div class="modal fade modal-lg" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="invoice/add" method="post"> <!-- Ubah ke route Anda -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <!-- Nama Pelanggan -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="nama" name="customer_name" required>
                    </div>

                    <!-- Nama Barang -->
                    <div class="mb-3">
                        <label class="form-label">Barang & Jumlah</label>
                        <div id="produk-container">
                            <!-- Baris pertama di dalam #produk-container -->
                            <template id="produk-template">
                                <div class="row mb-2 produk-item">
                                    <div class="col-7">
                                        <select name="produk_id[]" class="form-control produk-select" required>
                                            <option selected disabled>Pilih Barang</option>
                                            
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <input type="number" name="quantity[]" class="form-control" placeholder="Jumlah" required>
                                    </div>
                                    <div class="col-1 d-flex align-items-center">
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </template>


                        </div>
                        <button type="button" id="tambah-produk" class="btn btn-sm btn-outline-primary">+ Tambah Barang</button>
                    </div>

                
                    <!-- diskon -->
                    <div class="mb-3">
                        <label for="diskon" class="form-label">Diskon</label>
                        <input type="text" class="form-control" id="diskon" name="diskon" placeholder="Masukkan diskon Rp " >
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Belum Lunas">Belum Lunas</option>
                            <option value="Lunas">Lunas</option>
                            <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                        </select>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?= $this->endSection() ?>