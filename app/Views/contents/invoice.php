<?= $this->extend('layouts/index') ?>
<?= $this->section('content') ?>


<header class="topbar d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
    <h2 class="text-white fw-semibold text-uppercase small select-none mb-0">
        order
    </h2>
</header>

<?php if (session()->getFlashdata('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            toastr.success("<?= esc(session()->getFlashdata('success'), 'js') ?>");
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            toastr.error("<?= esc(session()->getFlashdata('error'), 'js') ?>");
        });
    </script>
<?php endif; ?>


<!-- Content Section -->
<section class="p-4 bg-white w-100">
    <div class="rounded-3 shadow-lg p-4 bg-white">
        <div class="mb-4">
            <div class="text-uppercase mb-4 text-secondary small fw-semibold select-none">
                Data Order
            </div>

            <!-- Search + Button -->
            <div class="d-flex justify-content-between mb-3">
                <form method="POST" action="invoice" class="d-flex" role="search">
                    <input class="form-control me-2" name="searchInvoice" type="search" placeholder="Cari Order  " aria-label="Search">
                    <select name="searchStatus" id="searchStatus" class="form-control">
                        <option value="0">Semua</option>
                        <option value="1">Menunggu Pembayaran</option>
                        <option value="2">Uang Muka</option>
                        <option value="3">Lunas</option>
                    </select>
                    <button style="margin-left: 8px;" class="btn btn-outline-secondary btn-sm" type="submit">Cari</button>
                </form>

                <!-- Small Modal Button -->
                <button type="button" class="btn btn-none" onclick="launchModal('tambah')">
                    <span class="btn btn-primary"> <i class="fas fa-plus"></i> Tambah Transaksi</span>
                </button>
                <!-- <button type="button" class="btn btn-none" data-bs-toggle="modal" data-bs-target="#modal">
                    <span class="btn btn-primary"> <i class="fas fa-plus"></i> Tambah Transaksi</span>
                </button> -->
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle dt-table1">
                    <thead class="table-light text-center">
                        <tr class="text-center">
                            <th scope="col">No</th>
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
                            <?php $no = 1;
                            $dataInvoices = [];
                            foreach ($invoices as $code => $invoice):
                                if ($invoice['status'] != '4' ) {
                                $dataInvoices[] = $invoice;
                                $rowspan = count($invoice['items']); ?>
                                <?php foreach ($invoice['items'] as $index => $item): ?>

                                    <?php
                                    $subtotal = $item['quantity'] * $item['price'];
                                    $totalHarga = $subtotal;
                                    ?>
                                    <tr class="text-center" <?= $invoice['status'] == 4 ? 'style="color:red;text-decoration: line-through;"' : ''; ?>>
                                        <?php if ($index == 0): ?>
                                            <td <?= $invoice['status'] == 4 ? 'style="color:red;"' : ''; ?> rowspan="<?= $rowspan ?>"><?= $no ?></td>
                                            <td <?= $invoice['status'] == 4 ? 'style="color:red;"' : ''; ?> rowspan="<?= $rowspan ?>"><?= $code ?></td>
                                            <td <?= $invoice['status'] == 4 ? 'style="color:red;"' : ''; ?> rowspan="<?= $rowspan ?>"><?= $invoice['customer_name'] ?></td>
                                        <?php endif; ?>

                                        <td <?= $invoice['status'] == 4 ? 'style="color:red;"' : ''; ?>><?= esc($item['name']) ?></td>
                                        <td <?= $invoice['status'] == 4 ? 'style="color:red;"' : ''; ?>><?= esc($item['quantity']) ?></td>
                                        <?php if ($index == 0): ?>
                                            <td <?= $invoice['status'] == 4 ? 'style="color:red;"' : ''; ?> rowspan="<?= $rowspan ?>"><?= esc($invoice['invoice_description']) ?></td>
                                        <?php endif; ?>

                                        <?php if ($index == 0): ?>
                                            <td <?= $invoice['status'] == 4 ? 'style="color:red;"' : ''; ?> rowspan="<?= $rowspan ?>">Rp <?= number_format($item['discount'], 0, ',', '.') ?></td>
                                        <?php endif; ?>

                                        <?php 
                                        if ($index == 0):
                                        $statusArr = [$invoice['status'] == 1 ? 'Menunggu Pembayaran':'Uang Muka', $invoice['status'] == 1 ? 'btn-opt1':'btn-opt2']; 
                                        $aaaa = '<div class="dropdown2">
                                            <button class="dropdown-toggles '.$statusArr[1].'"> '.$statusArr[0].'</button>
                                            <div class="dropdown-content">
                                            <div class="row">
                                                <a class="dropdown-option1" onclick="settleInvoice('.$invoice['invoice_id'].')">Melunaskan</a>
                                                <a class="dropdown-option2" onclick="cancelInvoice('.$invoice['invoice_id'].')">Membatalkan</a>
                                            </div>
                                            </div>
                                        </div>';
                                        ?>
                                            <td <?= $invoice['status'] == 4 ? 'style="color:red;"' : ''; ?> rowspan="<?= $rowspan ?>"><?= $invoice['status'] != '3' ? $aaaa :'<span class="btn btn-sm btn-success">Lunas</span>'; ?></td>
                                        <?php endif; ?>

                                        <td <?= $invoice['status'] == 4 ? 'style="color:red;"' : ''; ?>>Rp <?= number_format($totalHarga, 0, ',', '.') ?></td>

                                        <?php if ($index == 0): ?>
                                            <td rowspan="<?= $rowspan ?>" class="text-center align-middle">
                                                <!-- Tombol hanya sekali untuk 1 invoice -->
                                                <button class="btn" onclick="launchModal('edit', <?= $invoice['invoice_id'] ?>)"><i
                                                        class="fas fa-edit text-primary fs-5" title="<?= $invoice['updated_at'] ? 'Last Update: '.date('j F Y',strtotime($invoice['updated_at'])) : 'Created At: '.date('j F Y',strtotime($invoice['created_at'])) ?>&#10;<?= $invoice['updated_who'] ? 'Updated By: '.$invoice['user_name'] : 'Created By: '.$invoice['user_name'] ?>"></i></button>
                                                <a href="/cetak/print/<?= $code ?>" target="_blank" class="btn"><i
                                                        class="fas fa-file text-info fs-5"></i></a>
                                                <!-- <form id="deleteForm<?= $code ?>" action="<?= base_url('invoice/delete/' . $code) ?>"
                                                    method="post" class="d-inline">
                                                    <?= csrf_field() ?>
                                                </form> -->

                                                <!-- <form id="printForm<?= $code ?>"
                                                    action="<?= base_url('invoice/print-before-delete/' . $code) ?>" method="post"
                                                    target="_blank" class="d-none">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="code" value="<?= $code ?>">
                                                </form> -->

                                                <!-- <button type="button" class="btn" onclick="handlePrintAndDelete('<?= $code ?>')">
                                                    <i class="fas fa-trash text-danger fs-5"></i>
                                                </button> -->
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                                <?php $no++; ?>
                            <?php 
                                }
                            endforeach; 
                            ?>
                        <?php endif; ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Invoice -->
<div class="modal fade modal-lg" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="invoice/simpan" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <!-- Aksi Dan ID -->
                    <input type="hidden" name="aksi" class="form-control" id="aksi" value="">
                    <input type="hidden" name="invoice_id" class="form-control" id="id" value="">
                    <!-- Nama Pelanggan -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="nama" name="customer_name" required>
                    </div>

                    <!-- Alamat Pelanggan -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">Alamat Pelanggan</label>
                        <textarea class="form-control" id="alamat" name="address" maxlength="225" required></textarea>
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
                                            <?php foreach ($products as $p): ?>
                                                <option value="<?= htmlspecialchars($p['id']) ?>">
                                                    <?= htmlspecialchars($p['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <input type="number" name="quantity[]" class="form-control" placeholder="Jumlah"
                                            required>
                                    </div>
                                    <div class="col-1 d-flex align-items-center">
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus"><i
                                                class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <button type="button" id="tambah-produk" class="btn btn-sm btn-outline-primary">+ Tambah
                            Barang</button>
                    </div>


                    <!-- Diskon -->
                    <div class="mb-3">
                        <label for="diskon" class="form-label">Diskon</label>
                        <input type="text" class="form-control" id="diskon" name="diskon"
                            placeholder="Masukkan diskon Rp">
                    </div>

                    <!-- Status -->
                    <div class="mb-3" id="statusFORM">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="1">Menunggu Pembayaran</option>
                            <option value="2">Uang Muka</option>
                            <option value="3">Lunas</option>
                        </select>
                    </div>

                    <!-- descripsi -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="description" name="description"
                            placeholder="Masukan Desripsi">
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

<script src="js/form.js"></script>

<script>
    $(document).ready(function () {
        $('#searchStatus').select2({
            width: "100%",
        });
    })

    const dataFromPHP = <?php echo json_encode(isset($dataInvoices) ? $dataInvoices : []); ?>;

    const modalElement = document.getElementById('modal');
    const cloneModal = new bootstrap.Modal(modalElement);

    const inputAksi = document.getElementById('aksi');
    const inputId = document.getElementById('id');
    const inputNama = document.getElementById('nama');
    const inputAlamat = document.getElementById('alamat');
    const inputDiskon2 = document.getElementById('diskon');
    const inputDesc = document.getElementById('description');

    function launchModal(aksi, id = null) {
        document.querySelectorAll('.produk-item').forEach(item => item.remove());
        inputAksi.value = aksi;
        if (aksi == 'edit') {
            let data = dataFromPHP.find(item => item.invoice_id === String(id));
            if (data) {
                inputId.value = id;
                inputNama.value = data.customer_name;
                inputAlamat.value = data.address;
                inputDiskon2.value = data.discount;
                inputDesc.value = data.invoice_description;
                let items = data.items || [];
                items.forEach(e => {
                    addCart({
                        item_id: e.product_id,
                        quantity: e.quantity
                    });
                });
                const select = document.querySelector("#statusFORM");
                select.classList.add('d-none');
                // const option = select.querySelector(`option[value="${data.status}"]`);
                // option ? option.selected = true : console.log('Select Not Found')
            } else {
                console.error("Data with id " + id + " not found.");
            }
        } else {
            inputId.value = '';
            inputNama.value = '';
            inputAlamat.value = '';
            inputDiskon2.value = '';
            inputDesc.value = '';
            const select = document.querySelector("#statusFORM");
            select.classList.remove('d-none');
        }
        cloneModal.show();
    }

    function cancelInvoice(id) {
        Swal.fire({
            title: "Pembatalan Pesanan",
            text: "Kamu yakin ingin membatalkan pesanan?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, batalkan!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location="invoice/cancel/"+id
                // console.log("/cancel/"+id)
            }
        });
        }

    function settleInvoice(id) {
        Swal.fire({
            title: "Confirmasi Pelunasan",
            text: "Apakah ingin melanjutkan pelunasan?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, lunaskan!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location="invoice/settle/"+id
                // console.log("/cancel/"+id)
            }
        });
        }
</script>


<?= $this->endSection() ?>