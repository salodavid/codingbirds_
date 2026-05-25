<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/common/session/session.php';
require_once __DIR__ . '/common/connection/pos.urafiki.co.mz.php';

$pageTitle = 'Carregamentos';
$uid       = $sessionReseller['uid'];
$db        = Connection::get();

$stmt = $db->prepare(
    "SELECT bd.*, a.balance FROM TblBankDeposits bd
     JOIN TblResellers r ON bd.resellerId = r.id
     LEFT JOIN TblAccounts a ON a.resellerId = r.id
     WHERE r.uid = ? ORDER BY bd.createdAt DESC"
);
$stmt->bind_param('s', $uid);
$stmt->execute();
$deposits = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$stmt = $db->prepare(
    "SELECT a.balance FROM TblAccounts a JOIN TblResellers r ON a.resellerId = r.id WHERE r.uid = ? LIMIT 1"
);
$stmt->bind_param('s', $uid);
$stmt->execute();
$account = $stmt->get_result()->fetch_assoc();
$stmt->close();

$totalDeposited = array_sum(array_column($deposits, 'amount'));
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

  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="mb-0"><?= number_format($account['balance'] ?? 0, 2) ?> MT</h5>
          <p class="text-sm text-muted mb-0">Saldo Actual</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card text-center">
        <div class="card-body">
          <h5 class="mb-0"><?= number_format($totalDeposited, 2) ?> MT</h5>
          <p class="text-sm text-muted mb-0">Total Depositado (histórico)</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Histórico de Carregamentos</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Referência</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Confirmado por</th>
                  <th class="text-secondary text-xxs opacity-7">Data</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($deposits)): ?>
                <tr><td colspan="4" class="text-center text-sm py-4 text-muted">Nenhum carregamento registado.</td></tr>
                <?php else: ?>
                <?php foreach ($deposits as $d): ?>
                <tr>
                  <td class="ps-4">
                    <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($d['reference'] ?? '—') ?></p>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-xs font-weight-bold text-success"><?= number_format($d['amount'], 2) ?> MT</span>
                  </td>
                  <td><p class="text-xs text-secondary mb-0"><?= htmlspecialchars($d['confirmedBy'] ?? '—') ?></p></td>
                  <td><span class="text-xs text-secondary"><?= date('d/m/Y H:i', strtotime($d['createdAt'])) ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
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
