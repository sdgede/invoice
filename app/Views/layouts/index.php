
  <link rel="icon" href="<?= base_url('/img/logo.jpg') ?>" type="image/x-icon">

<div class="d-flex h-100">
  <?= $this->include('layouts/header') ?>

  <main class="flex-grow-1 d-flex flex-column overflow-auto" style="height: 100vh;">
    <?= $this->renderSection('content') ?>
  </main>

  <?= $this->include('layouts/footer') ?>
</div>
