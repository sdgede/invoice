<?= $this->extend('layouts/index') ?>
<?= $this->section('content') ?>

<header class="topbar d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
    <h2 class="text-white fw-semibold text-uppercase small select-none mb-0">
        User Data
    </h2>
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
            <div class="text-uppercase text-secondary small fw-semibold select-none">
                Data User
            </div>
            <div class="d-flex justify-content-end mb-3">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    Tambah User
                </button>

                <!-- Modal -->
                
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>username</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($users as $user): ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= esc($user['id']) ?></td>
                                <td><?= esc($user['name']) ?></td>
                                <td><?= esc($user['username']) ?></td>
                                <td><?= esc($user['created_at']) ?></td>
                                <td>
                                    <!-- Add your action buttons here -->
                                    <!-- Edit Button trigger modal -->
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $user['id'] ?>">
                                        Edit
                                    </button>

                                    <!-- Edit User Modal -->
                                    <div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?= $user['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="<?= site_url('user/edit/' . $user['id']) ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editUserModalLabel<?= $user['id'] ?>">Edit User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="name<?= $user['id'] ?>" class="form-label">Nama</label>
                                                            <input type="text" class="form-control" id="name<?= $user['id'] ?>" name="name" value="<?= esc($user['name']) ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="username<?= $user['id'] ?>" class="form-label">Username</label>
                                                            <input type="text" class="form-control" id="username<?= $user['id'] ?>" name="username" value="<?= esc($user['username']) ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="password<?= $user['id'] ?>" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                                            <input type="password" class="form-control" id="password<?= $user['id'] ?>" name="password">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="<?= site_url('user/delete/' . $user['id']) ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php $no++; endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form action="<?= site_url('user/add') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="modal-header">
                          <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                          </div>
                          <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                          </div>
                            
                          <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>