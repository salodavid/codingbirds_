<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/common/session/session.php';
require_once __DIR__ . '/code/summary.php';

$pageTitle = 'Painel Principal';
$today     = date('Y-m-d');
$uid       = $sessionReseller['uid'];
$summary   = getDashboardSummary($uid, $today);
$recent    = getRecentMobile($uid, 10);

$estadoLabel = fn($e) => match((int)$e) {
    1       => '<span class="badge badge-sm bg-gradient-success">Sucesso</span>',
    0       => '<span class="badge badge-sm bg-gradient-secondary">Pendente</span>',
    5       => '<span class="badge badge-sm bg-gradient-warning">Duplicado</span>',
    default => '<span class="badge badge-sm bg-gradient-danger">Erro</span>',
};
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

  <!-- KPI Cards -->
  <div class="row">
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-info shadow-dark text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">account_balance_wallet</i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Saldo Disponível</p>
            <h4 class="mb-0"><?= number_format($summary['balance'], 2) ?> MT</h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Conta activa</span></p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">phone_android</i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Mobile Hoje</p>
            <h4 class="mb-0"><?= $summary['mobile']['total'] ?> transacções</h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm">Débito: <span class="font-weight-bolder"><?= number_format($summary['mobile']['totalDebit'], 2) ?> MT</span></p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">tv</i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">TV Hoje</p>
            <h4 class="mb-0"><?= $summary['tv']['total'] ?> transacções</h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm">Débito: <span class="font-weight-bolder"><?= number_format($summary['tv']['totalDebit'], 2) ?> MT</span></p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
            <i class="material-icons opacity-10">bolt</i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Electricidade Hoje</p>
            <h4 class="mb-0"><?= $summary['elec']['total'] ?> transacções</h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm">Débito: <span class="font-weight-bolder"><?= number_format($summary['elec']['totalDebit'], 2) ?> MT</span></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Mobile Transactions -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Últimas Transacções Mobile — <?= date('d/m/Y') ?></h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">MSISDN</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Operador</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Débito</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipo</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Estado</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($recent)): ?>
                <tr><td colspan="6" class="text-center text-sm py-4 text-muted">Sem transacções hoje.</td></tr>
                <?php else: ?>
                <?php foreach ($recent as $t): ?>
                <tr>
                  <td class="ps-4">
                    <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($t['msisdn']) ?></p>
                    <p class="text-xs text-secondary mb-0"><?= date('H:i', strtotime($t['createdAt'])) ?></p>
                  </td>
                  <td>
                    <p class="text-xs text-secondary mb-0"><?= htmlspecialchars($t['prefix']) ?></p>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold"><?= number_format($t['amount'], 2) ?> MT</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold"><?= number_format($t['debit'], 2) ?> MT</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs"><?= htmlspecialchars($t['rechargeType']) ?></span>
                  </td>
                  <td class="align-middle text-center text-sm">
                    <?= $estadoLabel($t['estado']) ?>
                  </td>
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
<script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="assets/js/soft-ui-dashboard.min.js"></script>
</body>
</html>
