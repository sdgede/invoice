<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Bootstrap Page</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?= base_url('/css/bootstrap.min.css') ?>">

  <style>
    body, html {
      height: 100%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-container {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-card {
      background: #ffffffdd;
      border-radius: 1rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      max-width: 400px;
      width: 100%;
      padding: 2rem;
      backdrop-filter: blur(8px);
    }
    .login-card h2 {
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: #4b2a99;
      text-align: center;
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.3rem rgba(102, 126, 234, 0.5);
      border-color: #667eea;
    }
    .btn-login {
      background-color: #667eea;
      border: none;
      transition: background-color 0.3s ease-in-out;
    }
    .btn-login:hover {
      background-color: #5562d6;
    }
    .form-text a {
      color: #667eea;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    .form-text a:hover {
      color: #4551b8;
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="login-card shadow text-center">

      <!-- Logo -->
      <img src="<?= base_url('/img/logo.png') ?>" alt="Logo" width="200" class="mb-4" />

      <!-- Form Login -->
      <form method="post" action="/loginProcess">
        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger" role="alert">
            <?= session()->getFlashdata('error') ?>
          </div>
        <?php endif; ?>

        <div class="mb-3 text-start">
          <label for="inputUsername" class="form-label fw-semibold">Username</label>
          <input type="text" class="form-control" name="username" id="inputUsername" placeholder="Enter Username" required />
        </div>

        <div class="mb-3 text-start">
          <label for="inputPassword" class="form-label fw-semibold">Password</label>
          <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Password" required />
        </div>

        <button type="submit" class="btn btn-login w-100 text-white fw-semibold py-2">Log In</button>
      </form>

    </div>
  </div>

  <!-- Bootstrap JS (Jangan pakai <link> untuk JS!) -->
  <script src="<?= base_url('/js/bootstrap.min.js') ?>"></script>
</body>
</html>
