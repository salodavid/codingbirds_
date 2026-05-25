<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/common/session/session.php';
require_once __DIR__ . '/common/connection/pos.urafiki.co.mz.php';

$pageTitle = 'Parâmetros';
$uid       = $sessionReseller['uid'];
$db        = Connection::get();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'change_password') {
        $current  = $_POST['current_password'] ?? '';
        $new      = $_POST['new_password']     ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        if ($new !== $confirm) {
            $message = '<div class="alert alert-danger text-white">As passwords não coincidem.</div>';
        } elseif (strlen($new) < 6) {
            $message = '<div class="alert alert-danger text-white">A nova password deve ter pelo menos 6 caracteres.</div>';
        } else {
            $stmt = $db->prepare("SELECT password FROM TblResellers WHERE uid = ? LIMIT 1");
            $stmt->bind_param('s', $uid);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$row || !password_verify($current, $row['password'])) {
                $message = '<div class="alert alert-danger text-white">Password actual incorrecta.</div>';
            } else {
                $hashed = password_hash($new, PASSWORD_DEFAULT);
                $stmt   = $db->prepare("UPDATE TblResellers SET password = ? WHERE uid = ?");
                $stmt->bind_param('ss', $hashed, $uid);
                $stmt->execute();
                $stmt->close();
                $message = '<div class="alert alert-success text-white">Password alterada com sucesso.</div>';
            }
        }
    }
}

$stmt = $db->prepare("SELECT r.username, r.name, r.email FROM TblResellers r WHERE r.uid = ? LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
$info = $stmt->get_result()->fetch_assoc();
$stmt->close();

$stmt = $db->prepare("SELECT ipAddress FROM TblResellerIps ti JOIN TblResellers r ON ti.resellerId = r.id WHERE r.uid = ? AND ti.isActive = 5");
$stmt->bind_param('s', $uid);
$stmt->execute();
$ips = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>POS Urafiki — <?= $pageTitle ?></title>
  <link rel="icon" type="image/png" href="assets/img/logos/urafiki-icon.png">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="assets/css/nucleo-icons.css" rel="stylesheet">
  <link href="assets/css/nucleo-svg.css" rel="stylesheet">
  <link href="assets/css/soft-ui-dashboard.min.css" rel="stylesheet">
</head>
<body class="g-sidenav-show bg-gray-100">
<?php include __DIR__ . '/menu/menu.php'; ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
<?php include __DIR__ . '/menu/search.php'; ?>
<div class="container-fluid py-4">

  <?= $message ?>

  <div class="row">
    <!-- Account Info -->
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header pb-0"><h6>Dados da Conta</h6></div>
        <div class="card-body">
          <dl class="row">
            <dt class="col-sm-4 text-xs text-secondary">Utilizador</dt>
            <dd class="col-sm-8 text-xs font-weight-bold"><?= htmlspecialchars($info['username'] ?? '') ?></dd>
            <dt class="col-sm-4 text-xs text-secondary">Nome</dt>
            <dd class="col-sm-8 text-xs font-weight-bold"><?= htmlspecialchars($info['name'] ?? '') ?></dd>
            <dt class="col-sm-4 text-xs text-secondary">Email</dt>
            <dd class="col-sm-8 text-xs font-weight-bold"><?= htmlspecialchars($info['email'] ?? '') ?></dd>
            <dt class="col-sm-4 text-xs text-secondary">UID</dt>
            <dd class="col-sm-8 text-xs font-weight-bold text-muted"><?= htmlspecialchars($uid) ?></dd>
          </dl>
        </div>
      </div>
    </div>

    <!-- IPs autorizados -->
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header pb-0"><h6>IPs Autorizados</h6></div>
        <div class="card-body">
          <?php if (empty($ips)): ?>
          <p class="text-sm text-muted">Nenhum IP configurado (acesso aberto).</p>
          <?php else: ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($ips as $ip): ?>
            <li class="list-group-item px-0 text-xs"><?= htmlspecialchars($ip['ipAddress']) ?></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
          <p class="text-xxs text-muted mt-2">Para alterar os IPs autorizados contacte o administrador.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Change Password -->
  <div class="row">
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header pb-0"><h6>Alterar Password</h6></div>
        <div class="card-body">
          <form method="post">
            <input type="hidden" name="action" value="change_password">
            <div class="mb-3">
              <label class="form-label text-xs">Password Actual</label>
              <input type="password" name="current_password" class="form-control form-control-sm" required>
            </div>
            <div class="mb-3">
              <label class="form-label text-xs">Nova Password</label>
              <input type="password" name="new_password" class="form-control form-control-sm" required>
            </div>
            <div class="mb-3">
              <label class="form-label text-xs">Confirmar Nova Password</label>
              <input type="password" name="confirm_password" class="form-control form-control-sm" required>
            </div>
            <button type="submit" class="btn btn-sm bg-gradient-info">Alterar Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php include __DIR__ . '/footer/footer.php'; ?>
</div>
</main>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="assets/js/soft-ui-dashboard.min.js"></script>
</body>
</html>
