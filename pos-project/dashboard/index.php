<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>POS Urafiki — Acesso</title>
  <link rel="icon" type="image/png" href="assets/img/logos/urafiki-icon.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="assets/css/nucleo-icons.css" rel="stylesheet">
  <link href="assets/css/nucleo-svg.css" rel="stylesheet">
  <link href="assets/css/soft-ui-dashboard.min.css" rel="stylesheet">
</head>
<body class="">
<?php
$error = $_GET['error'] ?? '';
$errorMsg = match($error) {
    'empty'   => 'Preencha utilizador e password.',
    'invalid' => 'Credenciais inválidas. Tente novamente.',
    default   => ''
};
?>
<main class="main-content mt-0">
  <div class="page-header align-items-start min-vh-100" style="background-image: url('assets/img/curved-images/curved6.jpg');">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
      <div class="row">
        <div class="col-lg-4 col-md-8 col-12 mx-auto">
          <div class="card z-index-0 fadeIn3 fadeInBottom">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg py-3 pe-1 text-center">
                <h4 class="font-weight-bolder text-white mt-1">Bem vindo</h4>
                <p class="mb-1 text-sm text-white">Introduza as suas credenciais de acesso</p>
              </div>
            </div>
            <div class="card-body">
              <?php if ($errorMsg): ?>
              <div class="alert alert-danger text-white text-sm mb-3 py-2 px-3">
                <?= htmlspecialchars($errorMsg) ?>
              </div>
              <?php endif; ?>
              <form role="form" method="post" action="common/login/login.php">
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Utilizador" name="username" autocomplete="username" required>
                </div>
                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="Password" name="password" autocomplete="current-password" required>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Entrar</button>
                </div>
              </form>
            </div>
            <div class="card-footer text-center pt-0 px-lg-2 px-1">
              <p class="mb-2 text-sm">
                <small class="text-muted">© <?= date('Y') ?> Urafiki Lda — pos.urafiki.co.mz</small>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/soft-ui-dashboard.min.js"></script>
</body>
</html>
