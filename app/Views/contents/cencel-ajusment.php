<?= $this->extend('layouts/index') ?>
<?= $this->section('content') ?>

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

          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?= $this->endSection() ?>
