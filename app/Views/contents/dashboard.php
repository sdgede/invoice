<?= $this->extend('layouts/index') ?>
<?= $this->section('content') ?>

<header class="topbar d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
    <h2 class="text-white fw-semibold text-uppercase small select-none mb-0">
        Dashboard
    </h2>
</header>

<!-- Cards -->
<section class="bg-gradient-to-r p-4 d-grid gap-4"
    style="background: linear-gradient(90deg, #4f46e5 0%, #6366f1 100%); grid-template-columns: repeat(auto-fit,minmax(250px,1fr));">
    <!-- Card 1 -->
    <div class="position-relative bg-white rounded-3 p-4 shadow-sm">
        <div class="text-uppercase text-secondary small fw-semibold mb-1 select-none">
            Orderan Masuk
        </div>
        <div class="fs-1 mt-4 text-center fw-bold text-dark m-auto select-text">
            <?= $totalOrder ?? 0 ?>
        </div>
    </div>
    <!-- Card 2 -->
    <div class="position-relative bg-white rounded-3 p-4 shadow-sm">
        <div class="text-uppercase text-secondary small fw-semibold mb-1 select-none">
            Selesai
        </div>
        <div class="fs-1 mt-4 text-center fw-bold text-dark m-auto select-text">
            <?= $totalDone ?? 0 ?>
        </div>
    </div>
    <!-- Card 3 -->
    <div class="position-relative bg-white rounded-3 p-4 shadow-sm">
        <div class="text-uppercase text-secondary small fw-semibold mb-1 select-none">
            Total Pengeluaran
        </div>
        <div class="fs-2 fw-bold text-danger m-auto select-text">
            Rp <?= number_format($totalModal ?? 0, 0, ',', '.') ?>
        </div>
    </div>
    <!-- Card 4 -->
    <div class="position-relative bg-white rounded-3 p-4 shadow-sm">
        <div class="text-uppercase text-secondary small fw-semibold mb-1 select-none">
            Total Pendapatan
        </div>
        <div class="fs-2 fw-bold text-success m-auto select-text">
            Rp <?= number_format($totalPenjualan ?? 0, 0, ',', '.') ?>
        </div>
    </div>
</section>

<!-- Bottom content - Full Width -->
<section class="p-4 bg-white w-100">
    <div class="rounded-3 shadow-lg p-4 bg-white">
        <div class="mb-4">
            <div class="text-uppercase text-secondary small fw-semibold select-none">
                Data Stok Barang
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Ukuran</th>
                            <th scope="col">Warna</th>
                            <th scope="col">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
