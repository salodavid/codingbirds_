<?php
ob_start();
session_start();
if (!empty($_SESSION['reseller'])) { header('Location: main.php'); exit; }
$error = $_GET['error'] ?? '';
$errorMsg = match($error) {
    'empty'   => 'Preencha utilizador e password.',
    'invalid' => 'Credenciais inválidas. Tente novamente.',
    default   => ''
};
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>POS Urafiki — Acesso</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container d-flex align-items-center justify-content-center min-vh-100">
  <div class="card" style="max-width:420px; width:100%;">
    <div class="card-body p-5">
      <div class="text-center mb-4">
        <h1 class="h4 fw-bold mb-1">POS Urafiki</h1>
        <p class="text-muted small">Introduza as suas credenciais de acesso</p>
      </div>
      <?php if ($errorMsg): ?>
      <div class="alert alert-danger py-2 px-3 small"><?= htmlspecialchars($errorMsg) ?></div>
      <?php endif; ?>
      <form method="post" action="common/login/login.php">
        <div class="mb-3">
          <label class="form-label">Utilizador</label>
          <input type="text" class="form-control" name="username" placeholder="Utilizador" required autofocus autocomplete="username">
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" placeholder="Password" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </form>
      <p class="text-center mt-4 mb-0 small text-muted">© <?= date('Y') ?> Urafiki Lda</p>
    </div>
  </div>
</div>
<script src="assets/js/main.js" type="module"></script>
</body>
</html>
