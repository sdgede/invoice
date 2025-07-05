<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'WiboPrinting' ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
  <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.3.2/datatables.min.css" rel="stylesheet" integrity="sha384-dG72sN6C6+JA9moN/5eRa0GqXlYOpTivxgRRV4rTctUeb4ZNF6uuJ5NXmz+8+3Qi" crossorigin="anonymous">
  
  <link rel="stylesheet" href="<?= base_url('/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('/css/costume.css') ?>">
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/autoNumeric/4.10.4/autoNumeric.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/autoNumeric/1.9.46/autoNumeric-min.js"></script>
  <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.3.2/datatables.min.js" integrity="sha384-qLLX0jMaWXMZrun5/ry13tv5MX78CJNleGaaJVXRuJCDiAwyjhYWsTM3Qk3VaKC3" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>



<body>
  <!-- Sidebar -->
 <nav class="sidebar d-flex flex-column p-4" style="min-height: 100vh; background-color: #f9fafb;">
  <!-- Logo -->
  <div class="d-flex justify-content-center mb-4">
    <img src="<?= base_url('/img/logo.png') ?>" alt="Logo" width="150" />
  </div>

  <!-- Main Menu Section -->
  <div class="mb-2 fw-bold text-uppercase text-muted small">Main Menu</div>
  <ul class="nav flex-column mb-4">
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 text-dark" href="/">
        <i class="fas fa-box text-primary"></i>
        Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 text-warning" href="/invoice">
        <i class="fas fa-file-invoice"></i>
        Invoices
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 text-indigo" href="/category">
        <i class="fas fa-tags"></i>
        Category
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 text-info" href="/product">
        <i class="fas fa-warehouse"></i>
        Products
      </a>
    </li>
  </ul>

  <!-- Management Section -->
  <div class="mb-2 fw-bold text-uppercase text-muted small">Management</div>
  <ul class="nav flex-column mb-4">
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 text-secondary" href="/user">
        <i class="fas fa-users"></i>
        User Data
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 text-warning" href="/cancelled">
        <i class="fas fa-ban"></i>
        Invoice Cencel
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 text-success" href="/adjustment">
        <i class="fas fa-box"></i>
        Adjustment
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 text-info" href="/adjustment/cencel">
        <i class="fas fa-ban"></i>
        Adjust Censel
      </a>
    </li>
  </ul>

  <!-- Logout -->
  <div class="mt-auto">
    <a class="nav-link d-flex align-items-center gap-2 text-danger" href="/logout">
      <i class="fas fa-sign-out-alt"></i>
      Logout
    </a>
  </div>
</nav>
