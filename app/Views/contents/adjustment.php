<?= $this->extend('layouts/index') ?>
<?= $this->section('content') ?>

<!-- ======= TOAST FLASH ======= -->
<?php if ($msg = session()->getFlashdata('success')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => toastr.success(<?= json_encode($msg) ?>));
  </script>
<?php endif; ?>
<?php if ($msg = session()->getFlashdata('error')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => toastr.error(<?= json_encode($msg) ?>));
  </script>
<?php endif; ?>

<!-- ======= STYLES ======= -->
<style>
  .canceled-row td {
    text-decoration: line-through;
    opacity: .65;
  }
</style>

<header class="topbar d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
  <h2 class="text-white fw-semibold text-uppercase small mb-0">User Data</h2>
</header>

<section class="p-4 bg-white w-100">
  <div class="rounded-3 shadow-lg p-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <span class="text-uppercase text-secondary small fw-semibold">Data Adjustment</span>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Data</button>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>No</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Type</th>
            <th>Description</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($adjustments as $i => $row): ?>
            <?php
            $isCanceled = $row['status'] === 'canceled';
            $canRestore = $isCanceled && strtotime($row['canceled_at']) >= strtotime('-3 days');
            ?>
            <tr class="<?= $isCanceled ? 'canceled-row' : '' ?>">
              <td><?= $i + 1 ?></td>
              <td><?= esc($row['product_name']) ?></td>
              <td><?= $row['quantity'] ?></td>
              <td><?= ['', 'Gagal Produksi', 'Di Sumbangkan', 'Dll'][$row['type']] ?? '—' ?></td>
              <td><?= esc($row['description']) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
              <td>
                <?php if (!$isCanceled): ?>
                  <!-- EDIT BUTTON -->
                  <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>

                  <!-- CANCEL BUTTON -->
                  <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $row['id'] ?>">Cancel</button>
                <?php elseif ($canRestore): ?>
                  <!-- RESTORE BUTTON -->
                  <form action="<?= site_url('adjustment/restore/' . $row['id']) ?>" method="post" style="display:inline;">
                    <?= csrf_field() ?>
                    <button class="btn btn-sm btn-warning" onclick="return confirm('Restore & kurangi stok lagi?')">Restore</button>
                  </form>
                <?php else: ?>
                  <span class="badge bg-secondary">Expired</span>
                <?php endif; ?>
              </td>
            </tr>

            <!-- ===== MODAL CANCEL ===== -->
            <div class="modal fade" id="cancelModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="cancelLabel<?= $row['id'] ?>" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="<?= site_url('adjustment/cancel/' . $row['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                      <h5 class="modal-title" id="cancelLabel<?= $row['id'] ?>">Alasan Pembatalan</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Stok akan dikembalikan. Harap isi alasan:</p>
                      <textarea name="reason" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <button class="btn btn-danger" type="submit">Confirm Cancel</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- ===== MODAL EDIT ===== -->
            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editLabel<?= $row['id'] ?>" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="<?= site_url('adjustment/update/' . $row['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                      <h5 class="modal-title" id="editLabel<?= $row['id'] ?>">Edit Adjustment</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label">Product</label>
                        <select name="product" class="form-control select3" required>
                          <?php foreach ($products as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= $p['id'] == $row['product_id'] ? 'selected' : '' ?>><?= esc($p['name']) ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" min="1" class="form-control" value="<?= $row['quantity'] ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="adjust" class="form-control" required>
                          <option value="1" <?= $row['type'] == 1 ? 'selected' : '' ?>>Gagal Produksi</option>
                          <option value="2" <?= $row['type'] == 2 ? 'selected' : '' ?>>Di Sumbangkan</option>
                          <option value="3" <?= $row['type'] == 3 ? 'selected' : '' ?>>Dll</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control" required><?= esc($row['description']) ?></textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- ======= MODAL ADD ======= -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= site_url('adjustment/create') ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title">Tambah Adjustment</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Product</label>
            <select name="product"
                    class="form-control select2"
                    required>
              <option value="" disabled selected>— pilih —</option>
              <?php foreach ($products as $p): ?>
                <option value="<?= $p['id'] ?>"><?= esc($p['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" min="1"
                   class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="adjust"
            class="form-control"
                    required>
              <option value="1">Gagal Produksi</option>
              <option value="2">Di Sumbangkan</option>
              <option value="3">Dll</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3"
                      class="form-control" required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ======= JS ======= -->
<script>
  // helper biar nggak ngetik ulang
  function initSelect2($ctx) {
    $ctx.find('.select2').select2({
      width: '100%',
      dropdownParent: $('#addModal')
    });
  }

  // pertama kali halaman dimuat
  document.addEventListener('DOMContentLoaded', () => {
    initSelect2($('#addModal'));
  });

  // kalau modal dibuka lagi, re‑init (buat konten dinamis)
  $('#addModal').on('shown.bs.modal', function () {
    initSelect2($(this));
  });


  function initSelect3($ctx) {
    $ctx.find('.select3').select2({
      width: '100%',
      dropdownParent: $('#editModal')
    });
  }

  // pertama kali halaman dimuat
  document.addEventListener('DOMContentLoaded', () => {
    initSelect3($('#editModal'));
  });

  // kalau modal dibuka lagi, re‑init (buat konten dinamis)
  $('#editModal').on('shown.bs.modal', function () {
    initSelect3($(this));
  });
</script>


<?= $this->endSection() ?>